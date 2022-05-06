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

    protected array $optinConfirm = [
      "confirm" => [
        "title" => "Falta pouco! Confirme seu cadastro.",
        "desc" => "Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro e comece a controlar com o CaféControl",
        "image" => ""
      ],
      "welcome" => [
        "title" => "Tudo pronto. Você já pode controlar :)",
        "desc" => "Bem-vindo(a) ao seu controle de contas, vamos tomar um café?",
        "image" => "",
        "link" => "",
        "linkTitle" => "Fazer Login"
      ],
    ];

    /**
     * Redirection based in logged user access level
     *
     * @param array|null $data
     */
    public function root(?array $data): void
    {
        if (user() && user()->access_level_id == 3) {
            redirect($this->router->route("dash.client"));
        }
        elseif (user() && user()->access_level_id == 2) {
            redirect($this->router->route("dash.operator"));
        }
        elseif (user() && user()->access_level_id == 1) {
            redirect($this->router->route("dash.admin"));
        }
        else {
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
        $this->redirectIfUserIsLogged();

        $head = $this->seo->render(
          "Login - " . CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url("/entrar"),
          theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-login", [
          "head" => $head,
          "router" => $this->router,
          "cookie" => filter_input(INPUT_COOKIE, "authEmail")
        ]);
    }

    /**
     * @param array|null $data
     */
    public function loginExecute(?array $data): void
    {
        if (request_limit("weblogin", 3, 60 * 5)) {
            $json['message'] = $this->message
              ->error("Você já efetuou 3 tentativas, esse é o limite. Por favor, aguarde 5 minutos para tentar novamente!")
              ->render();
            echo json_encode($json);
            return;
        }

        if (empty($data['email']) || empty($data['password'])) {
            $json['message'] = $this->message
              ->warning("Informe seu email e senha para entrar")
              ->render();
            echo json_encode($json);
            return;
        }

        $save = (!empty($data['save']) ? true : false);
        $auth = new User();
        $login = $auth->login($data['email'], $data['password'], $save);

        if ($login) {
            $this->message
              ->success("Seja bem-vindo(a) de volta " . user()->name . "!")
              ->flash();
            $this->redirect($this->router->route("login.root"));
        }
        else {
            $json['message'] = $auth->message()->before("Ooops! ")->render();
        }

        echo json_encode($json);
        return;
    }

    /**
     * @param array|null $data
     */
    public function registerForm(?array $data): void
    {
        $this->redirectIfUserIsLogged();

        $head = $this->seo->render(
          "Criar Conta - " . CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url("/cadastrar"),
          theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-register", [
          "head" => $head,
          "router" => $this->router,
        ]);
    }

    /**
     * @param array|null $data
     */
    public function registerExecute(?array $data): void
    {
        if (in_array("", $data)) {
            $json['message'] = $this->message->info("Informe seus dados para criar sua conta.")->render();
            echo json_encode($json);
            return;
        }

        $user = new User();

        $data = filter_var_array($data, FILTER_SANITIZE_STRIPPED);
        $user->fill($data);
        if (!is_passwd($user->password)) {
            $min = CHAMPS_PASSWD_MIN_LEN;
            $max = CHAMPS_PASSWD_MAX_LEN;
            $json['message'] = $this->message->warning("A senha deve ter entre {$min} e {$max} caracteres");
            echo json_encode($json);
            return;
        } else {
            $user->password = passwd($user->password);
        }

        if ($user->register($user)) {
            $json['redirect'] = $this->router->route("login.confirm");
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

        $head = $this->seo->render(
          "Recuperar Senha - " . CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url("/recuperar"),
          theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-forget", [
          "head" => $head,
          "router" => $this->router,
        ]);

    }

    /**
     * @param array|null $data
     */
    public function forgetExecute(?array $data): void
    {
        if (empty($data["email"])) {
            $json['message'] = $this->message->info("Informe seu e-mail para continuar")->render();
            echo json_encode($json);
            return;
        }

        if (request_repeat("webforget", $data["email"])) {
            $json['message'] = $this->message->error("Ooops! Você já tentou este e-mail antes")->render();
            echo json_encode($json);
            return;
        }

        $auth = new User();
        if ($auth->forget($data["email"])) {
            $json["message"] = $this->message->success("Acesse seu e-mail para recuperar a senha")->render();
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

        $head = $this->seo->render(
          "Crie sua nova senha no " . CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url("/recuperar"),
          theme("/assets/images/share.jpg")
        );

        echo $this->view->render("auth-reset", [
          "head" => $head,
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
            $json["message"] = $this->message->info("Informe e repita a senha para continuar")->render();
            echo json_encode($json);
            return;
        }

        [$email, $code] = explode("|", $data["code"]);
        $auth = new User();

        if ($auth->reset($email, $code, $data["password"], $data["password_re"])) {
            $this->message->success("Senha alterada com sucesso. Vamos controlar?")->flash();
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
        $head = $this->seo->render(
          "Confirme Seu Cadastro - " . CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url("/confirma"),
          theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
          "head" => $head,
          "router" => $this->router,
          "data" => (object)$this->optinConfirm['confirm']
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

        $head = $this->seo->render(
          "Bem-vindo(a) ao " . CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url("/w"),
          theme("/assets/images/share.jpg")
        );

        echo $this->view->render("optin", [
          "head" => $head,
          "router" => $this->router,
          "data" => (object)$this->optinConfirm["welcome"],
          "track" => (object)[
            "fb" => "Lead",
            "aw" => "AW-953362805/yAFTCKuakIwBEPXSzMYD"
          ]
        ]);
    }

    /**
     *
     */
    protected function redirectIfUserIsLogged():void
    {
        if (user()) {
            redirect($this->router->route("login.root"));
        }
    }

    public function logout(?array $data): void
    {
        /** @var User $user */
        $user = user();
        if($user){
            $user::logout();
        }
        redirect($this->router->route("login.root"));
    }

}