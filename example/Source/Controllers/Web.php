<?php


namespace Source\Controllers;


use BetoCampoy\ChampsFramework\Controller\Controller;

class Web extends Controller
{
    protected ?string $pathToViews = __DIR__."/../../Theme/Example/";

    public function list(?array $data)
    {
        echo $this->view->render("view", []);
    }

    public function error(?array $data)
    {
        var_dump([
          "erro"
        ]);
    }
}