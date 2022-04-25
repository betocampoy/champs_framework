<?php

namespace Source\Controllers;


use BetoCampoy\ChampsFramework\Controller\Controller;
use Source\Models\User\User;

class Users extends Controller
{

    protected $pathToViews = __DIR__."/../../Theme/Example/";

    public function home(?array $data)
    {
        $users = new User();
        echo $this->view->render("users/users", ["users" => $users]);
    }

    public function error(?array $data)
    {
        var_dump([
          "erro"
        ]);
    }
}