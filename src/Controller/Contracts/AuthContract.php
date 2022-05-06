<?php

namespace BetoCampoy\ChampsFramework\Controller\Contracts;

use BetoCampoy\ChampsFramework\Router\Router;

interface AuthContract
{

    public function __construct(Router $router);

    public function root(?array $data):void;

    public function loginForm(?array $data):void;

    public function loginExecute(?array $data):void;

    public function registerForm(?array $data):void;

    public function registerExecute(?array $data):void;

    public function forgetForm(?array $data):void;

    public function forgetExecute(?array $data):void;

    public function resetForm(?array $data):void;

    public function resetExecute(?array $data):void;

    public function confirm(?array $data):void;

    public function welcome(?array $data):void;

    public function logout(?array $data):void;

}