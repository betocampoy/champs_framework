<?php


namespace BetoCampoy\ChampsFramework\Admin;



use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Navigation;
use BetoCampoy\ChampsFramework\Navbar\Navbar;
use BetoCampoy\ChampsFramework\Navbar\Templates\Bootstrap5;
use BetoCampoy\ChampsFramework\Router\Router;

class ChampsAdmin extends Controller
{
    protected ?string $pathToViews = __DIR__ . "/theme/";

    protected Navbar $navbar;

    protected string $title = "Administrative Panel";

    public function __construct(Router $router)
    {
        parent::__construct($router);

        $this->navbar = (new Bootstrap5())
            ->setRootItem("Home", "/champsframework")
            ->setRootItem("Authentication")
            ->setChildItem("Roles", "/champsframework/roles")
            ->setChildItem("Permissions", "/champsframework/permissions")
            ->setChildItem("Users", "/champsframework/users")
            ->setRootItem("Navigation", "/champsframework/navigation")
            ->setRootItem("Reports", "/champsframework/reports");
    }

    public function home(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/home", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
        ]);
    }

    public function navigationList(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/navigation/list", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
            "navigations" => (new Navigation())->order("theme ASC")
        ]);
    }

}