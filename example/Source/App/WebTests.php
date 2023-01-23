<?php

namespace Source\App;

use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Navbar\Templates\Bootstrap3;

/**
 * Class WebTests
 * @package Source\App
 */
class WebTests extends Controller
{
    public function __call($name, $arguments)
    {
        $seo = $this->seo->render(
            CHAMPS_SEO_SITE_NAME,
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(),
            theme('/asset/champs-logo.png')
        );

        echo $this->view->render("$name", [
            "seo" => $seo
        ]);
    }

    /**
     * @param array|null $data
     */
    public function home(?array $data): void
    {
        if (is_theme_minified("web") && !file_exists(__CHAMPS_THEME_DIR__ . "/web/assets/priority.css")) {
            $this->redirect("/do-minify");
        }

        $seo = $this->seo->render(
            CHAMPS_SEO_SITE_NAME . " Home",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        /* mount navbar */
        $navbar = (new Bootstrap3())
            ->setRootItem("Home", "/")
            ->setRootItem("Teste1")
            ->setChildItem("subteste1", "/teste1")
            ->setChildItem("subteste2", "/teste2")
            ->setRootItem("Teste2", "/teste3");

        $page = $data['page'] ?? 'home';
        echo $this->view->render($page, [
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $navbar,
        ]);
    }

    public function logout(?array $data): void
    {
        $this->redirect($this->router->route("logout"));
    }



}