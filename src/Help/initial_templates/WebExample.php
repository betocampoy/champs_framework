<?php

namespace Source\App;

use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Auth\User;

use BetoCampoy\ChampsFramework\Pager;

/**
 * Example Web Controller
 * @package Source\App
 */
class WebExample extends Controller
{
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

    /**
     * SITE TERMS
     */
    public function terms(): void
    {
        $seo = $this->seo->render(
            CHAMPS_SITE_NAME . " Terms",
            CHAMPS_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        echo $this->view->render("terms", [
            "router" => $this->router,
            "seo" => $seo
        ]);
    }

    /**
     * CONTACT TERMS
     */
    public function contact(): void
    {
        $seo = $this->seo->render(
            CHAMPS_SITE_NAME . " Contact",
            CHAMPS_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        echo $this->view->render("contact", [
            "router" => $this->router,
            "seo" => $seo
        ]);
    }

}