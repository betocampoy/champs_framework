<?php


namespace BetoCampoy\ChampsFramework\Controller;


use BetoCampoy\ChampsFramework\Controller\Contracts\AuthContract;

class AuthController extends Controller implements AuthContract
{

    public function loginForm(?array $data): void
    {
        var_dump("teste");
    }

    public function loginExecute(?array $data): void
    {
        // TODO: Implement loginExecute() method.
    }

    public function registerForm(?array $data): void
    {
        // TODO: Implement registerForm() method.
    }

    public function registerExecute(?array $data): void
    {
        // TODO: Implement registerExecute() method.
    }

    public function forgetForm(?array $data): void
    {
        // TODO: Implement forgetForm() method.
    }

    public function forgetExecute(?array $data): void
    {
        // TODO: Implement forgetExecute() method.
    }

    public function resetForm(?array $data): void
    {
        // TODO: Implement resetForm() method.
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