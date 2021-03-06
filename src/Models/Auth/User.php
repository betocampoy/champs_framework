<?php

namespace BetoCampoy\ChampsFramework\Models\Auth;


use BetoCampoy\ChampsFramework\Models\Report\Online;
use BetoCampoy\ChampsFramework\ORM\Model;
use BetoCampoy\ChampsFramework\Session;

/**
 * Class User
 *
 * @package BetoCampoy\ChampsFramework\Models\Auth
 */
class User extends Model
{
    protected array $protected = ["id"];
    protected array $required = ["email", "password"];

    public function __construct()
    {
        if(defined('CHAMPS_AUTH_ENTITY') && !empty(CHAMPS_AUTH_ENTITY)){
            $this->entity = CHAMPS_AUTH_ENTITY;
        }

        if(defined('CHAMPS_AUTH_REQUIRED_FIELDS') && !empty(CHAMPS_AUTH_REQUIRED_FIELDS)){
            $this->required = array_merge($this->required, CHAMPS_AUTH_REQUIRED_FIELDS);
        }
        parent::__construct();
    }


    /**
     * ###   RELATIONSHIPS   ###
     */


    /**
     * @return Model|null
     */
    public static function user(): ?Model
    {
        $session = new Session();
        if (!$session->has("authUser")) {
            return null;
        }

        return (new User())->findById($session->authUser);
    }

    /**
     * @return Model|null
     */
    public function accessLevel():?Model
    {
        return $this->belongsTo(AccessLevel::class, 'access_level_id');
    }

    /**
     * @param int|null $id
     *
     * @return Model|null
     */
    public function roles(int $id = null):?Model
    {
        return $this->belongsToMany(Role::class, UserHasRole::class, null, 'user_id', $id);
    }

    /**
     * ###   PREPARE SET DATA   ###
     */

    /**
     * @param string $value
     *
     * @return string
     */
    public function prepareEmail(string $value):string
    {
        return strtolower(str_remove_diacritic(str_fix_spaces($value)));
    }

    /**
     * ###   AUTH METHODS   ###
     */

    /**
     * logout
     */
    public static function logout(): void
    {
        $session = new Session();
        if($session->has('online')){
            (new Online())->findById($session->online)->destroy();
        }
        $session->unset("authUser");
    }

    /**
     * @param string $email
     * @param string $columns
     *
     * @return array|mixed|null|Model
     */
    public function findByEmail(string $email, string $columns = "*")
    {
        $find = $this->find("email = :email", "email={$email}", $columns);
        return $find->fetch();
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     *
     * @return Model|null
     */
    public function hasRoles(string $terms = null, string $params = null) :?Model
    {
        if($terms){
            $terms = "AND $terms";
        }
        if($params){
            $params = "&$params";
        }

        return (new UserHasRole())
          ->find("m.user_id=:user_id {$terms}", "user_id={$this->id}{$params}", "m.*, j.name")
          ->join(Role::class, "m.role_id=j.id");
    }

    /**
     * @param string $role_name
     *
     * @return bool
     */
    public function hasRole(string $role_name):bool
    {
        $role = $this->hasRoles("j.name=:name", "name={$role_name}")->count();
        if($role > 0){
            return true;
        }

        return false;
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     *
     * @return Model|null
     */
    public function hasPermissions(string $terms = null, string $params = null) :?Model
    {
        $role_terms = "";
        $role_params = "";
        $roles = $this->hasRoles();

        if($roles->count()) {

            foreach ($roles->fetch(true) as $userHasRole) {
                $role_id = $userHasRole->role_id;
                $role_terms = $role_terms ? "{$role_terms}, :role_id{$role_id}"
                  : ":role_id{$role_id}";
                $role_params = $role_params
                  ? "{$role_params}&role_id{$role_id}={$role_id}"
                  : "role_id{$role_id}={$role_id}";
            }

            if ($terms) {
                $terms = "AND $terms";
            }
            if ($params) {
                $params = "&$params";
            }
        }

        $result = (new RoleHasPermission())
          ->find("m.role_id IN ({$role_terms}) {$terms}", "{$role_params}{$params}", "DISTINCT m.permission_id, j.name")
          ->join(Permission::class, "m.permission_id=j.id");

        return $result;
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission_name):bool
    {

        $permission = $this->hasPermissions("j.name=:name", "name={$permission_name}")->count();

        if($permission){
            return true;
        }

        return false;
    }

    /**
     * @param \BetoCampoy\ChampsFramework\Models\Auth\User $user
     *
     * @return bool
     */
    public function register(User $user): bool
    {
        if (!$user->save()) {
            $this->setMessage("error", "Falha ao registrar");
            return false;
        }

        $email = $this->createEmail("ConfirmEmail", $user);

//        $view = new View(__DIR__ . "/../../shared/views/email");
//        $message = $view->render("confirm", [
//            "nome" => $user->nome,
//            "confirm_link" => url("/obrigado/" . base64_encode($user->email))
//        ]);
//
//        (new Email())->bootstrap(
//            "Ative sua conta no " . CONF_SITE_NAME,
//            $message,
//            $user->email,
//            "{$user->nome}"
//        )->send();

        return true;
    }

    /**
     * Perform the login sequence
     *
     * @param string $email
     * @param string $password
     * @param bool $save
     * @param int $level
     * @return bool
     */
    public function login(string $email, string $password, bool $save = false, int $level = 3): bool
    {

        $user = $this->attempt($email, $password, $level);
        if (!$user) {
            return false;
        }

        if ($save) {
            setcookie("authEmail", $email, time() + 604800, "/");
        } else {
            setcookie("authEmail", null, time() - 3600, "/");
        }

        //LOGIN
        (new Session())->set("authUser", $user->id)->unset('menu');
        //        $user->montaMenu();
        return true;
    }

    /**
     * Attempt to login with the email and pass
     *
     * @param string $email
     * @param string $password
     * @param int    $level
     *
     * @return Model|null
     */
    public function attempt(string $email, string $password, int $level = 1): ?Model
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->setMessage("warning", "O e-mail informado n??o ?? v??lido");
            return null;
        }

        if (!is_passwd($password)) {
            $this->setMessage("warning", "A senha informada n??o ?? v??lida");
            return null;
        }

        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->messages['error'][] = "O e-mail informado n??o est?? cadastrado";
            return null;
        }

        if (!$user->password) {
            if($this->forget($email)){
                $this->setMessage("warning", "Usu??rio ainda n??o foi validado");
            }
            return null;
        }

        if (!passwd_verify($password, $user->password)) {
            $this->setMessage('error', "A senha informada n??o confere");
            return null;
        }

        if (passwd_rehash($user->password)) {
            $user->password = $password;
            $user->save();
        }

        return $user;
    }

    /**
     * Send the confirmation email
     *
     * @param string $email
     *
     * @return bool
     */
    public function confirm(string $email): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->setMessage('error', "O e-mail informado n??o est?? cadastrado.");
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        $user->password = passwd(md5(uniqid(rand(), true)));
        $user->save();

        $email = $this->createEmail("ConfirmEmail", $user);
        if($email && !$email->queue()){
            $this->setMessage("error", "Fail to save in the queue");
            return false;
        }

        return true;
    }

    /**
     * Send the forget password email
     * @param string $email
     * @return bool
     */
    public function forget(string $email): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->setMessage("warning", "O e-mail informado n??o est?? cadastrado.");
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        if(!$user->save()){
            $this->setMessage("warning", "Falha ao salvar.");
            return false;
        }

        $email = $this->createEmail("ForgetEmail", $user);
        if($email){
            $email->queue();
        }

        return true;
    }

    /**
     * @param string $email
     * @param string $code
     * @param string $password
     * @param string $passwordRe
     * @return bool
     */
    public function reset(string $email, string $code, string $password, string $passwordRe): bool
    {
        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->setMessage("warning", "A conta para recupera????o n??o foi encontrada.");
            return false;
        }

        if ($user->forget != $code) {
            $this->setMessage('error', "Desculpe, mas o c??digo de verifica????o n??o ?? v??lido.");
            return false;
        }

        if (!is_passwd($password)) {
            $min = defined('CHAMPS_PASSWD_MIN_LEN') ? CHAMPS_PASSWD_MIN_LEN : 5;
            $max = defined('CHAMPS_PASSWD_MAX_LEN') ? CHAMPS_PASSWD_MAX_LEN : 50;
            $this->setMessage("info", "Sua senha deve ter entre {$min} e {$max} caracteres.");
            return false;
        }

        if ($password != $passwordRe) {
            $this->setMessage("warning", "Voc?? informou duas senhas diferentes.");
            return false;
        }

        $user->password = passwd($password);
        $user->forget = null;
        $user->validado = true;
        $user->save();
        return true;
    }

    /**
     * @param string                                       $template
     * @param User $user
     *
     * @return mixed
     */
    protected function createEmail(string $template, User $user)
    {
        $vendorEmailClass = "\\BetoCampoy\\ChampsFramework\\Email\\Templates\\{$template}";
        $appEmailClass = "\\Source\\Support\\MailTemplates\\{$template}";
        if(class_exists($appEmailClass)){
            return new $appEmailClass($user, [
              "name" => $user->name,
              "confirm_link" => url("/forget/{$user->email}|{$user->forget}")
            ]);
        }else{
            return new $vendorEmailClass($user, [
              "name" => $user->name,
              "confirm_link" => url("/forget/{$user->email}|{$user->forget}")
            ]);
        }
    }

    /**
     * ###   GETTERS   ###
     */


    /**
     * @return string|null
     */
    public function photo():?string
    {
        if ($this->photo
          && file_exists(
            __DIR__ .
            "/../../../../../../" .
            CHAMPS_STORAGE_ROOT_FOLDER .
            "/" .
            CHAMPS_STORAGE_IMAGE_FOLDER .
            "/{$this->photo}")
        ) {
            return $this->photo;
        }

        return null;
    }
}