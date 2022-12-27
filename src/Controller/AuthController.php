<?php


namespace BetoCampoy\ChampsFramework\Controller;


use BetoCampoy\ChampsFramework\Controller\Contracts\AuthContract;
use BetoCampoy\ChampsFramework\Models\Auth\User;

/**
 * Class AuthController
 *
 * @package BetoCampoy\ChampsFramework\Controller
 */
class AuthController extends Controller implements AuthContract
{
    protected bool $protectedController = false;

//    protected array $optinConfirm = [
//        "confirm" => [
//            "title" => "Falta pouco! Confirme seu cadastro.",
//            "desc" => "Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro e comece a controlar com o CaféControl",
//            "image" => ""
//        ],
//        "welcome" => [
//            "title" => "Tudo pronto. Você já pode controlar :)",
//            "desc" => "Bem-vindo(a) ao seu controle de contas, vamos tomar um café?",
//            "image" => "",
//            "link" => "",
//            "linkTitle" => "Fazer Login"
//        ],
//    ];

    /**
     * Redirection based in logged user access level
     *
     * @param array|null $data
     */
    public function root(?array $data): void
    {
        if (user() && user()->access_level_id == 3) {
            redirect($this->router->route("dash.client"));
        } elseif (user() && user()->access_level_id == 2) {
            redirect($this->router->route("dash.operator"));
        } elseif (user() && user()->access_level_id == 1) {
            redirect($this->router->route("dash.admin"));
        } else {
            redirect($this->router->route("login.form"));
        }
    }

    /**
     * Load the login form named [auth-login].
     *
     * Important: The protected ?string $pathToViews = __DIR__ . "/path/to/auth/pages/";
     *
     * @param array|null $data
     */
    public function loginForm(?array $data): void
    {
        $this->performValidation();
        $this->redirectIfUserIsLogged();

        $seo = $this->seo->render(
            champs_messages("login_form_title", ['site' => CHAMPS_SITE_NAME]),
            CHAMPS_SITE_DESCRIPTION,
            $this->router->route("login.form"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-login", [
            "seo" => $seo,
            "router" => $this->router,
            "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }

    /**
     * @param array|null $data
     */
    public function loginExecute(?array $data): void
    {
        if(isset($data['email']) && filter_var($data['email'], FILTER_SANITIZE_STRIPPED)){
            $authKey = filter_var($data['email'], FILTER_SANITIZE_STRIPPED);
        }elseif(isset($data['login']) && filter_var($data['login'], FILTER_SANITIZE_STRIPPED)){
            $authKey = filter_var($data['login'], FILTER_SANITIZE_STRIPPED);
        }elseif(isset($data['document']) && filter_var($data['document'], FILTER_SANITIZE_STRIPPED)){
            $authKey = filter_var($data['document'], FILTER_SANITIZE_STRIPPED);
        }elseif(isset($data['mobile']) && filter_var($data['mobile'], FILTER_SANITIZE_STRIPPED)){
            $authKey = filter_var($data['mobile'], FILTER_SANITIZE_STRIPPED);
        }else{
            $authKey = null;
        }

        if (request_limit("weblogin", CHAMPS_AUTH_REQUEST_LIMIT_TRIES, 60 * CHAMPS_AUTH_REQUEST_LIMIT_MINUTES)) {
            $json['message'] = $this->message
                ->error(champs_messages("attempts_exceeded", ["minutes" => 60 * CHAMPS_AUTH_REQUEST_LIMIT_MINUTES]))
                ->render();
            echo json_encode($json);
            return;
        }

        if (empty($authKey) || empty($data['password'])) {
            $json['message'] = $this->message
                ->warning(champs_messages("login_mandatory_data"))
                ->render();
            echo json_encode($json);
            return;
        }

        $save = (!empty($data['save']) ? $data['save'] : false);
        $auth = new User();
        $login = $auth->login($authKey, $data['password'], $save);

        if ($login) {
            $this->message
                ->success(champs_messages("login_welcome", ["user" => user()->name]))
                ->flash();
            $this->redirect($this->router->route("login.root"));
        } else {
            $json['message'] = $auth->message()->before("Ooops! ")->render();
        }

        echo json_encode($json);
        return;
    }

    /**
     * This method is responsible to control the facebook authorization
     *
     * @param array|null $data
     */
    public function callbackFacebook(?array $data)
    {
        $state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_STRIPPED);
        $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_STRIPPED);

        if(!$code){
            // code not found
            redirect($this->router->route("login.root"));
            return;
        }

        $provider = new \League\OAuth2\Client\Provider\Facebook([
            'clientId' => CHAMPS_OAUTH_FACEBOOK['app_id'],
            'clientSecret' => CHAMPS_OAUTH_FACEBOOK['app_secret'],
            'redirectUri' => CHAMPS_OAUTH_FACEBOOK['app_callback'],
            'graphApiVersion' => CHAMPS_OAUTH_FACEBOOK['app_version'],
        ]);

        if (empty($state) || ($state !== session()->oauth2state)) {
            $this->message->error(champs_messages("facebook_fail", ["code" => "Invalid State"]))->flash();
            session()->unset('oauth2state');
            redirect($this->router->route("login.root"));
            return;
        }

        /* Try to get an access token (using the authorization code grant) */
        try {
            $token = $provider->getAccessToken('authorization_code', ['code' => $code]);
        }catch (\Exception $e){
            $this->message->error(champs_messages("facebook_fail", ["code" => "Invalid Token"]))->flash();
            redirect($this->router->route("login.root"));
            return;
        }

        try {
            // We got an access token, let's now get the user's details

            /** @var \League\OAuth2\Client\Provider\FacebookUser $userFacebook */
            $userFacebook = $provider->getResourceOwner($token);

            // if there is an user logged, save facebook_id in database
            $loggedUser = user();
            if($loggedUser){
                $loggedUser->facebook_id = $userFacebook->getId();
                $loggedUser->save();
                $this->message->success(
                    champs_messages(
                        "facebook_linked"
                        ,["facebook_user" => $userFacebook->getName()]
                    ))->flash();
                redirect(url());
                return;
            }

            /**
             * Any user is logged in application
             */

            /* search if the facebook_id is already registred */
            $userByFbId = (new User())->where("facebook_id=:facebook_id", "facebook_id={$userFacebook->getId()}")->fetch() ?? null;
            $userByEmail = (new User())->where("email=:email", "email={$userFacebook->getEmail()}")->fetch() ?? null;

            /* if not, search if the user's e-mail is registred */
            if(!$userByFbId && $userByEmail){
                /* The e-mail was found, updated user in database */
                $userByEmail->facebook_id = $userFacebook->getId();
                $userByEmail->save();
                $userByFbId = clone $userByEmail;
            }

            /* If both facebook_id nor email exists, register the new user (if activated) */
            if(!$userByFbId && !$userByEmail){

                if(CHAMPS_OPTIN_ROUTES_CREATE){
                    $newUser = (new User());
                    $newUser->name = $newUser->columnExists("last_name") ? $userFacebook->getFirstName() : $userFacebook->getName();
                    if($newUser->columnExists("last_name")) $newUser->last_name = $userFacebook->getLastName();
                    $newUser->email = $userFacebook->getEmail();
                    $newUser->facebook_id = $userFacebook->getId();

                    (new User())->register($newUser);

                    $this->message->warning(
                        champs_messages(
                            "optin_register_success", ["email" => $userFacebook->getEmail()]
                        ))->flash();
                    redirect(url());
                    return;
                }

                $this->message->warning(
                    champs_messages(
                        "login_user_not_registered"))->flash();
                redirect(url());
                return;
            }

            // verify if user isn't active
            if(!$userByFbId->active){
                $this->message
                    ->warning(champs_messages("login_user_disabled"))
                    ->flash();
                redirect($this->router->route("login.root"));
            }

            // user authorized, procede the login
            session()->set("authUser", $userByFbId->id)->unset('menu');
            $this->message
                ->success(champs_messages("login_welcome", ["user" => user()->name]))
                ->flash();
            redirect($this->router->route("login.root"));
            return;

        }catch (\Exception $e){
            $this->message->error(champs_messages("facebook_fail", ["code" => "Invalid User"]))->flash();
            redirect($this->router->route("login.root"));
            return;
        }

    }

    /**
     * @param array|null $data
     */
    public function registerForm(?array $data): void
    {
        $this->redirectIfUserIsLogged();

        $seo = $this->seo->render(
            champs_messages("optin_register_form_title", ['site' => CHAMPS_SITE_NAME]),
            CHAMPS_SITE_DESCRIPTION,
            $this->router->route("register.form"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-register", [
            "seo" => $seo,
            "router" => $this->router,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function registerExecute(?array $data): void
    {
        if (!array_key_exists("name", $data) || !filter_var($data['name'], FILTER_SANITIZE_STRIPPED)) {
            $json['message'] = $this->message->info(
                champs_messages("mandatory_field_missing", ["field" => "Name"])
            )->render();
            echo json_encode($json);
            return;
        }

        if (!array_key_exists("email", $data) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $json['message'] = $this->message->info(
                champs_messages("mandatory_field_missing", ["field" => "E-mail"])
            )->render();
            echo json_encode($json);
            return;
        }

        if (!array_key_exists("password", $data) || !filter_var($data['password'], FILTER_SANITIZE_STRIPPED)) {
            $json['message'] = $this->message->info(
                champs_messages("mandatory_field_missing", ["field" => "Password"])
            )->render();
            echo json_encode($json);
            return;
        }

        if (!array_key_exists("password_re", $data) || !filter_var($data['password_re'], FILTER_SANITIZE_STRIPPED)) {
            $json['message'] = $this->message->info(
                champs_messages("mandatory_field_missing", ["field" => "Confirm Password"])
            )->render();
            echo json_encode($json);
            return;
        }

        if ($data['password'] != $data['password_re']) {
            $json['message'] = $this->message->info(
                champs_messages("password_confirm_incorrect")
            )->render();
            echo json_encode($json);
            return;
        }

        if((new User())->findByEmail($data['email'])->count() > 0){
            $json['message'] = $this->message->warning(champs_messages("optin_register_email_exists"));
            echo json_encode($json);
            return;
        }

        $user = new User();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $user->fill($data);
        if (!is_passwd($user->password)) {
            $min = CHAMPS_PASSWD_MIN_LEN;
            $max = CHAMPS_PASSWD_MAX_LEN;
            $json['message'] = $this->message->warning(champs_messages("optin_register_invalid_pass", ["min" => $min, "max" => $max]));
            echo json_encode($json);
            return;
        } else {
            $user->password = passwd($user->password);
        }

        if ($user->register($user)) {
            $json['redirect'] = $this->router->route("register.confirm");
        } else {
            $json['message'] = $user->message()->before("Ooops! ")->render();
        }

        echo json_encode($json);
        return;
    }

    /**
     * @param array|null $data
     */
    public function forgetForm(?array $data): void
    {
        $this->redirectIfUserIsLogged();

        $seo = $this->seo->render(
            champs_messages("forget_form_title", ['site' => CHAMPS_SITE_TITLE]),
            CHAMPS_SITE_DESCRIPTION,
            $this->router->route("forget.form"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-forget", [
            "seo" => $seo,
            "router" => $this->router,
        ]);

    }

    /**
     * @param array|null $data
     */
    public function forgetExecute(?array $data): void
    {
        if (empty($data["email"])) {
            $json['message'] = $this->message->info(champs_messages("login_forget_mandatory_data"))->render();
            echo json_encode($json);
            return;
        }

        if (request_repeat("webforget", $data["email"])) {
            $json['message'] = $this->message->error(champs_messages("forget_repeat"))->render();
            echo json_encode($json);
            return;
        }

        $auth = new User();
        if ($auth->forget($data["email"])) {
            $json["message"] = $this->message->success(champs_messages("forget_email_sent"))->render();
        } else {
            $json["message"] = $auth->message()->before("Ooops! ")->render();
        }

        echo json_encode($json);
        return;
    }

    /**
     * @param array|null $data
     */
    public function resetForm(?array $data): void
    {
        $this->redirectIfUserIsLogged();

        $seo = $this->seo->render(
            champs_messages("reset_form_title", ["site" => CHAMPS_SITE_TITLE]),
            CHAMPS_SITE_DESCRIPTION,
            $this->router->route("reset.form"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-reset", [
            "seo" => $seo,
            "code" => $data["code"],
            "router" => $this->router,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function resetExecute(?array $data): void
    {
        if (empty($data["password"]) || empty($data["password_re"])) {
            $json["message"] = $this->message->info(champs_messages("reset_password_confirm"))->render();
            echo json_encode($json);
            return;
        }

        [$email, $code] = explode("|", $data["code"]);
        $auth = new User();

        if ($auth->reset($email, $code, $data["password"], $data["password_re"])) {
            $this->message->success(champs_messages("reset_password_success"))->flash();
            $json["redirect"] = $this->router->route("login.root");
        } else {
            $json["message"] = $auth->message()->before("Ooops! ")->render();
        }

        echo json_encode($json);
        return;
    }

    /**
     * @param array|null $data
     */
    public function confirm(?array $data): void
    {
        $seo = $this->seo->render(
            champs_messages("optin_confirm_form_title", ['site' => CHAMPS_SITE_TITLE]),
            CHAMPS_SITE_DESCRIPTION,
            $this->router->route("confirm"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-optin", [
            "seo" => $seo,
            "router" => $this->router,
            "data" => (object)[
                "title" => "Falta pouco! Confirme seu cadastro.",
                "desc" => "Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro e comece a controlar com o CaféControl",
                "image" => theme("/assets/images/optin-confirm.jpg")
            ]
        ]);
    }

    /**
     * @param array|null $data
     */
    public function welcome(?array $data): void
    {
        $email = base64_decode($data["email"]);
        $user = (new User())->findByEmail($email);

        if ($user && $user->status != "confirmed") {
            $user->status = "confirmed";
            $user->save();
        }

        $seo = $this->seo->render(
            champs_messages("optin_welcome_form_title", ['site' => CHAMPS_SITE_TITLE]),
            CHAMPS_SITE_DESCRIPTION,
            $this->router->route("welcome"),
            theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
            "seo" => $seo,
            "router" => $this->router,
            "data" => (object)[
                "title" => "Tudo pronto. Você já pode controlar :)",
                "desc" => "Bem-vindo(a) ao seu controle de contas, vamos tomar um café?",
                "image" => theme("/assets/images/optin-success.jpg"),
                "link" => url("/entrar"),
                "linkTitle" => "Fazer Login"
            ],
        ]);
    }

    /**
     *
     */
    protected function redirectIfUserIsLogged(): void
    {
        if (user()) {
            redirect($this->router->route("login.root"));
        }
    }

    public function logout(?array $data): void
    {
        /** @var User $user */
        $user = user();
        if ($user) {
            $user::logout();
        }
        redirect($this->router->route("login.root"));
    }

}