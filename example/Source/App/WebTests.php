<?php

namespace Source\App;

use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Navbar\Bootstrap3;

/**
 * Class WebTests
 * @package Source\App
 */
class WebTests extends Controller
{
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

    /**
     * SITE HOME
     */
    public function home(): void
    {
        if (is_theme_minified("web") && !file_exists(__CHAMPS_THEME_DIR__."/web/assets/priority.css")){
            $this->redirect("/do-minify");
        }

        $seo = $this->seo->render(
            CHAMPS_SITE_NAME . " Home",
            CHAMPS_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        echo $this->view->render("home", [
            "router" => $this->router,
            "seo" => $seo,
        ]);
    }

    public function navbar(): void
    {

//        session()->unset('navbar');die();
        $navbar = new Bootstrap3();
        echo $navbar->render();
        var_dump($_SESSION);
    }

}