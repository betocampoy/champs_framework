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

    public function __construct()
    {
        $this->entity = CHAMPS_AUTH_MODEL_ENTITY;
        $this->required = array_merge(["email"], CHAMPS_AUTH_MODEL_REQUIRED_FIELDS);

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
    public function accessLevel(): ?Model
    {
        return $this->belongsTo(AccessLevel::class, 'access_level_id');
    }

    /**
     * @param int|null $id
     *
     * @return Model|null
     */
    public function roles(int $id = null): ?Model
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
    public function prepareEmail(string $value): string
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
        if ($session->has('online')) {
            (new Online())->findById($session->online)->destroy();
        }
        $session->unset("authUser");
        $session->unset("masterAdmin");
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
    public function hasRoles(string $terms = null, string $params = null): ?Model
    {
        if ($terms) {
            $terms = "AND $terms";
        }
        if ($params) {
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
    public function hasRole(string $role_name): bool
    {
        $role = $this->hasRoles("j.name=:name", "name={$role_name}")->count();
        if ($role > 0) {
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
    public function hasPermissions(string $terms = null, string $params = null): ?Model
    {
        $role_terms = "";
        $role_params = "";
        $roles = $this->hasRoles();

        if ($roles->count()) {

            foreach ($roles->fetch(true) as $userHasRole) {
                $rolesIds[] = $userHasRole->role_id;
//                $role_id = $userHasRole->role_id;
//                $role_terms = $role_terms ? "{$role_terms}, :role_id{$role_id}"
//                    : ":role_id{$role_id}";
//                $role_params = $role_params
//                    ? "{$role_params}&role_id{$role_id}={$role_id}"
//                    : "role_id{$role_id}={$role_id}";
            }

//            if ($terms) {
//                $terms = "AND $terms";
//            }
//            if ($params) {
//                $params = "&$params";
//            }
        }

        $result = (new RoleHasPermission())
            ->join(Permission::class, "m.permission_id=j.id")
            ->columns("DISTINCT m.permission_id, j.name")
            ->whereIn("m.role_id", $rolesIds)
            ->where($terms, $params);

        return $result;
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission_name): bool
    {
        $permission = $this->hasPermissions("j.name=:name", "name={$permission_name}")->count();
        if ($permission) {
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
        if (!CHAMPS_MAIL_ENABLED) {
            $this->setMessage("error", champs_messages("mail_not_enabled", ['operation' => "User Registration"]));
            return false;
        }

        if (!$user->save()) {
            $this->setMessage("error", champs_messages("model_persist_fail", ["model" => "User"]));
            return false;
        }

        $email = $this->createEmail("ConfirmEmail", $user, url("/optin/welcome/" . base64_encode($user->email)));
        if ($email && !$email->queue()) {
            $this->setMessage("error", champs_messages("email_queue_fail"));
            return false;
        }
        return true;
    }

    /**
     * Perform the login sequence
     *
     * @param string $userKey
     * @param string $password
     * @param bool $save
     * @param int $level
     * @return bool
     */
    public function login(string $userKey, string $password, bool $save = false, int $level = 3): bool
    {

        $user = $this->attempt($userKey, $password, $level);
        if (!$user) {
            return false;
        }

        if ($save) {
            setcookie("authKey", $userKey, time() + 604800, "/");
        } else {
            setcookie("authKey", null, time() - 3600, "/");
        }

        //LOGIN
        (new Session())->set("authUser", $user->id)->unset('menu');
        setcookie("weblogin", null, time() - 3600, "/");
        return true;
    }

    /**
     * Attempt to login with the email and pass
     *
     * @param string $userKey
     * @param string $password
     * @param int $level
     * @return Model|null
     */
    public function attempt(string $userKey, string $password, int $level = 3): ?Model
    {

        if (!is_passwd($password)) {
            $this->setMessage("warning", champs_messages("password_invalid"));
            return null;
        }

        $user = null;
        if (filter_var($userKey, FILTER_VALIDATE_EMAIL)) {
            $user = (new User())->findByEmail($userKey);
        }
        var_dump($user);die();
        if (!$user && valid_cpf($userKey) && $this->columnExists("document")) {
            $user = (new User())->where("document=:document", "document={$userKey}")->fetch();
        }

        if (!$user && $this->columnExists("mobile")) {
            $mobile = str_only_numbers($userKey);
            $user = (new User())->where("mobile=:mobile", "mobile={$mobile}")->fetch();
        }

        if (!$user) {
            $this->setMessage("error", champs_messages("registry_not_found_in_model", ["model" => "User"]));
            return null;
        }

        if (!$user->password && $user->email) {
            if ($this->forget($user->email)) {
                $this->setMessage("warning", champs_messages("login_user_not_validated"));
            }
            return null;
        }

        if (!passwd_verify($password, $user->password)) {
            $this->setMessage('error', champs_messages("password_incorrect"));
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
        if (!CHAMPS_MAIL_ENABLED) {
            return false;
        }

        $user = (new User())->findByEmail($email);

        if (!$user) {
            $this->setMessage('error', champs_messages("registry_not_found_in_model", ["model" => "e-mail"]));
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        $user->password = passwd(md5(uniqid(rand(), true)));
        $user->save();

        $email = $this->createEmail("ConfirmEmail", $user);
        if ($email && !$email->queue()) {
            $this->setMessage("error", champs_messages("email_queue_fail"));
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
            $this->setMessage("warning", champs_messages("registry_not_found_in_model", ["model" => "User"]));
            return false;
        }

        $user->forget = md5(uniqid(rand(), true));
        if (!$user->save()) {
            $this->setMessage("warning", champs_messages("model_persist_fail", ["model" => "User"]));
            return false;
        }

        $email = $this->createEmail("ForgetEmail", $user);
        if ($email && !$email->queue()) {
            $this->setMessage("error", champs_messages("email_queue_fail"));
            return false;
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
            $this->setMessage("warning", champs_messages("registry_not_found_in_model", ["model" => "E-mail"]));
            return false;
        }

        if ($user->forget != $code) {
            $this->setMessage('error', champs_messages("reset_password_validation_code_invalid"));
            return false;
        }

        if (!is_passwd($password)) {
            $min = CHAMPS_PASSWD_MIN_LEN;
            $max = CHAMPS_PASSWD_MAX_LEN;
            $this->setMessage("info", champs_messages("optin_register_invalid_pass", ["min" => $min, "max" => $max]));
            return false;
        }

        if ($password != $passwordRe) {
            $this->setMessage("warning", champs_messages("password_confirm_incorrect"));
            return false;
        }

        $user->password = passwd($password);
        $user->forget = null;
        $user->validado = true;
        $user->save();
        return true;
    }

    /**
     * @param string $template
     * @param User $user
     * @param string|null $ctaLink
     * @return mixed
     */
    protected function createEmail(string $template, User $user, ?string $ctaLink)
    {
        $vendorEmailClass = "\\BetoCampoy\\ChampsFramework\\Email\\Templates\\{$template}";
        $appEmailClass = "\\Source\\Support\\Email\\Templates\\{$template}";
        if (class_exists($appEmailClass)) {
            return new $appEmailClass($user, [
                "name" => $user->name,
                "confirm_link" => $ctaLink ?? null
            ]);
        } else {
            return new $vendorEmailClass($user, [
                "name" => $user->name,
                "confirm_link" => $ctaLink ?? null
            ]);
        }
    }

    /**
     * ###   GETTERS   ###
     */


    /**
     * @return string|null
     */
    public function photo(): ?string
    {
        if ($this->photo
            && file_exists(
                __CHAMPS_DIR__ . "/" . CHAMPS_STORAGE_ROOT_FOLDER . "/" . CHAMPS_STORAGE_IMAGE_FOLDER . "/{$this->photo}")
        ) {
            return $this->photo;
        }

        return null;
    }
}