<?php


namespace BetoCampoy\ChampsFramework\Admin;



use BetoCampoy\ChampsFramework\Controller\Controller;

class ChampsAdmin extends Controller
{
    protected ?string $pathToViews = __DIR__ . "/theme/";

    public function home(?array $data = null): void
    {
        $seo = $this->seo->render(
            CHAMPS_SITE_NAME . " Home",
            CHAMPS_SITE_DESCRIPTION,
            url(),
            theme("/assets/images/favicon.ico")
        );

        echo $this->view->render("widgets/home", [
            "router" => $this->router,
            "seo" => $seo,
        ]);
    }

}