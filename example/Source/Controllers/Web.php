<?php


namespace Source\Controllers;


use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Router\Router;
use BetoCampoy\ChampsFramework\Seo;

class Web extends Controller
{
    protected ?string $pathToViews = __DIR__."/../../Theme/Example/";

    public function __call($name, $arguments)
    {
        $seo = $this->seo->render(
          CHAMPS_SITE_NAME,
          CHAMPS_SITE_DESC,
          url(),
          theme('/asset/champs-logo.png')
        );

        echo $this->view->render("$name", [
          "seo" => $seo
        ]);
    }

//    public function list(?array $data)
//    {
//        $seo = $this->seo->render(
//          CHAMPS_SITE_NAME,
//          CHAMPS_SITE_DESC,
//          url(),
//          theme('/asset/champs-logo.png')
//        );
//
//        echo $this->view->render("list", [
//          "seo" => $seo
//        ]);
//    }
//
//    public function install(?array $data)
//    {
//        $seo = $this->seo->render(
//          CHAMPS_SITE_NAME . "Installing the framework",
//          CHAMPS_SITE_DESC,
//          url(),
//          theme('/asset/champs-logo.png')
//        );
//
//        echo $this->view->render("install", [
//          "seo" => $seo
//        ]);
//    }

    public function error(?array $data)
    {
        var_dump([
          "erro"
        ]);
    }
}