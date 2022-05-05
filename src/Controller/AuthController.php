<?php


namespace BetoCampoy\ChampsFramework\Controller;


use BetoCampoy\ChampsFramework\Controller\Contracts\AuthContract;
use BetoCampoy\ChampsFramework\Models\Auth\User;

class AuthController extends Controller implements AuthContract
{
    protected bool $protectedController = false;

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
            $this->redirect($this->router->route("dash"));
        }
        else {
            $json['message'] = $auth->message()->before("Ooops! ")->render();
        }

        echo json_encode($json);
        return;
    }


    public function registerExecute(?array $data): void
    {
        // TODO: Implement registerExecute() method.
    }

    public function forgetExecute(?array $data): void
    {
        // TODO: Implement forgetExecute() method.
    }

    public function resetExecute(?array $data): void
    {
        // TODO: Implement resetExecute() method.
    }

    public function confirm(?array $data): void
    {
        // TODO: Implement confirm() method.
    }

    public function welcome(?array $data): void
    {
        // TODO: Implement welcome() method.
    }

}