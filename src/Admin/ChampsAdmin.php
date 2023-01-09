<?php


namespace BetoCampoy\ChampsFramework\Admin;


use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Navigation;
use BetoCampoy\ChampsFramework\Navbar\Navbar;
use BetoCampoy\ChampsFramework\Navbar\Templates\Bootstrap5;
use BetoCampoy\ChampsFramework\Pager;
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

    /***********************
     * NAVIGATION
     ***********************/

    public function navigationHome(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/navigation/home", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
            "navigations" => (new Navigation())
        ]);
    }

    public function navigationList(?array $data = null): void
    {
        $navigations = (new Navigation())->order("theme_name ASC, display_name ASC");

        $search = null;
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $navigations->where("MATCH(display_name) AGAINST(:s)", "s={$search}");
            if (!$navigations->count()) {
                $this->message->info(champs_messages("registers_not_found_in_model"))->flash();
                $this->router->redirect($this->router->route("champs.admin.navigationList"));
            }
        }

        $all = ($search ?? "all");
        $page = !empty($data["page"]) ? $data["page"] : 1;
        $pager = new Pager($this->router->route("champs.admin.navigationPager", ["search" => $all]));
        $totalCounter = $navigations->count();
        $pager->pager($totalCounter, CHAMPS_PAGER_LIMIT, $page, 2);
        $navigations->limit($pager->limit())->offset($pager->offset())->order("display_name DESC");

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
            "navigations" => $navigations,
            "pager" => $pager
        ]);
    }

    public function navigationCreate(?array $data = null): void
    {
        $json['modalFormBS5']['form'] = $this->view->render("widgets/navigation/modal_create", [
            "router" => $this->router,
            "root_items" => Navigation::rootItens(),
        ]);
        echo json_encode($json);
        return;
    }

    public function navigationEdit(?array $data = null): void
    {
        $navigation = (new Navigation())->findById($data['id']);
        $navSequences = (new Navigation())->setTheme($navigation->theme_name)->order("sequence ASC");
        if($navigation->parent_id > 0){
            $navSequences->where("parent_id=:parent_id", "parent_id={$navigation->parent_id}");
        }else{
            $navSequences->where("parent_id IS NULL");
        }
//        $json['modalFormBS5']['id'] = "myModalTest";
        $json['modalFormBS5']['form'] = $this->view->render("widgets/navigation/modal_edit", [
            "router" => $this->router,
            "navigation" => $navigation,
            "root_items" => Navigation::rootItens(),
            "sequences" => $navSequences
        ]);
        echo json_encode($json);
        return;
    }

    public function navigationUpdate(?array $data = null): void
    {
        /* faz as validações */

        $navigation = (new Navigation())->findById($data['id']);
        $navigation->fill($data);
        $navigation->save();
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }
}