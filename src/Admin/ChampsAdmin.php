<?php


namespace BetoCampoy\ChampsFramework\Admin;


use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Auth\Permission;
use BetoCampoy\ChampsFramework\Models\Auth\User;
use BetoCampoy\ChampsFramework\Models\Navigation;
use BetoCampoy\ChampsFramework\Navbar\Navbar;
use BetoCampoy\ChampsFramework\Navbar\Templates\Bootstrap5;
use BetoCampoy\ChampsFramework\Pager;
use BetoCampoy\ChampsFramework\Router\Router;
use BetoCampoy\ChampsFramework\Support\Validator\Validators\NavigationValidator;
use BetoCampoy\ChampsFramework\Support\Validator\Validators\PermissionValidator;

class ChampsAdmin extends Controller
{
    protected ?string $pathToViews = __DIR__ . "/theme/";

    protected Navbar $navbar;

    protected string $title = "Administrative Panel";

    public function __construct(Router $router)
    {
        parent::__construct($router);

        $this->navbar = (new Bootstrap5())
            ->setSaveInSession(false)
            ->setNavbarSessionName("ChampsNavAdmPanel")
            ->setRootItem("Home", "/champsframework")
            ->setRootItem("Authentication", "/champsframework/auth")
            ->setRootItem("Navigation", "/champsframework/navigation")
            ->setRootItem("Parameters", "/champsframework/parameters")
            ->setRootItem("Reports", "/champsframework/reports");
    }


    public function loginForm(?array $data = null): void
    {
        $usrLogged = $route = $this->validation();
        if ($usrLogged['logged']) {
            $this->router->redirect($usrLogged['route']);
        }

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/login", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar = (new Bootstrap5())
                ->setSaveInSession(false)
                ->setNavbarSessionName("ChampsNavAdmPanel")
                ->setRootItem("Home", "/champsframework"),
        ]);
    }

    public function login(?array $data = null): void
    {
        $email = filter_var($data['master_admin_email'], FILTER_VALIDATE_EMAIL);
        $password = $data['master_admin_password'];

        if (!$email || !$password) {
            $json['message'] = $this->message->error("Enter the e-mail and password for login!")->render();
            echo json_encode($json);
            return;
        }

        $credentials = __get_framework_parameter('CHAMPS_SESSION_MASTER_ADMIN_USER');
        if ($email != $credentials['email']) {
            $json['message'] = $this->message->error("The e-mail is invalid!")->render();
            echo json_encode($json);
            return;
        }

        if (!password_verify($password, $credentials['password'])) {
            $json['message'] = $this->message->error("Password invalid!")->render();
            echo json_encode($json);
            return;
        }


        session()->set("master_admin", $credentials);
        $json['redirect'] = $this->router->route("champs.admin.home");
        echo json_encode($json);
    }

    protected function validation(): array
    {
        $authEntityExists = (new User())->entityExists();
        if (!$authEntityExists && !session()->has("master_admin")) {
            /* Authentication not activated - show login offline form */
            return ["logged" => false, "route" => $this->router->route("champs.admin.loginForm")];
        }

        $credentials = __get_framework_parameter('CHAMPS_SESSION_MASTER_ADMIN_USER');
        if (!$authEntityExists && session()->has("master_admin") && (array)session()->master_admin != $credentials) {
            /* Authentication not activated - show login offline form */
            User::logout();
            return ["logged" => false, "route" => $this->router->route("champs.admin.loginForm")];
        }

        if ($authEntityExists && (!\user() || !is_admin())) {
            /* non authorized access */
            User::logout();
            return ["logged" => false, "route" => $this->router->route("login.form")];
        }
        return ["logged" => true, "route" => $this->router->route("champs.admin.home")];
    }

    public function home(?array $data = null): void
    {
        $usrLogged = $route = $this->validation();
        if (!$usrLogged['logged']) {
            $this->router->redirect($usrLogged['route']);
        }

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

    /*******************************
     * AUTHENTICATION
     ******************************/

    /*******************************
     * AUTHENTICATION - PERMISSIONS
     ******************************/

    public function authHome(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/auth/home", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
        ]);
    }

    /*******************************
     * AUTHENTICATION - PERMISSIONS
     ******************************/

//    public function permissionsHome(?array $data = null): void
//    {
//        $seo = $this->seo->render(
//            $this->title,
//            CHAMPS_SITE_DESCRIPTION,
//            url(current_url()),
//            __champsadm_theme("/assets/images/favicon.ico?123"),
//            false
//        );
//
//        echo $this->view->render("widgets/auth/permissions/list", [
//            "title" => $this->title,
//            "router" => $this->router,
//            "seo" => $seo,
//            "navbar" => $this->navbar,
//            "permissions" => (new Permission())
//        ]);
//    }

    public function permissionsSearch(?array $data = []): void
    {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            $json['redirect'] = $this->router->route("champs.admin.permissionsSearchGet", ["search" => $s, "page" => 1]);
            echo json_encode($json);
            return;
        }
        $json['redirect'] = $this->router->route("champs.admin.permissionsSearchGet", ["search" => "all", "page" => 1]);
        echo json_encode($json);
        return;

    }

    public function permissionsList(?array $data = null): void
    {
        $permissions = (new Permission())->order("name ASC");

        $search = null;
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $permissions->where("MATCH(name) AGAINST(:s)", "s={$search}");
            if (!$permissions->count()) {
                $this->message->info(champs_messages("registers_not_found_in_model"))->flash();
                $this->router->redirect($this->router->route("champs.admin.permissionsList"));
            }
        }

        $all = ($search ?? "all");
        $page = !empty($data["page"]) ? $data["page"] : 1;
        $pager = new Pager($this->router->route("champs.admin.permissionsPager", ["search" => $all]));
        $totalCounter = $permissions->count();
        $pager->pager($totalCounter, 10, $page, 2);
        $permissions->limit($pager->limit())->offset($pager->offset())->order("name DESC");

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/auth/permissions/list", [
            "seo" => $seo,
            "title" => $this->title,
            "router" => $this->router,
            "navbar" => $this->navbar,
            "permissions" => $permissions,
            "pager" => $pager
        ]);
    }

    public function permissionsCreate(?array $data = null): void
    {
        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions/modal_create", [
            "router" => $this->router,
        ]);
        echo json_encode($json);
        return;
    }

    public function permissionsSave(?array $data = null): void
    {
        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
        $validator = new PermissionValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $permission = (new Permission());
        $permission->fill($data);
        if (!$permission->save()) {
            var_dump($permission);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function permissionsEdit(?array $data = null): void
    {
        $permission = (new Permission())->findById($data['id']);

        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions/modal_edit", [
            "router" => $this->router,
            "permission" => $permission,
        ]);
        echo json_encode($json);
        return;
    }

    public function permissionsUpdate(?array $data = null): void
    {
        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
        $validator = new NavigationValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $navigation = (new Navigation())->findById($data['id']);
        $navigation->fill($data);
        $navigation->save();
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function permissionsDelete(?array $data = null): void
    {
        /* faz as validações */

        $permission = (new Permission())->findById($data['id']);
        $name = $permission->name;
        if (!$permission->destroy()) {
            var_dump($permission);
            die();
        }
        $this->message->success("The item {$name} deleted from database")->flash();
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function permissionsFilterRoot(?array $data = null)
    {
        $themeName = isset($data['theme_name']) ? filter_var($data['theme_name'], FILTER_SANITIZE_STRING) : null;
        $rootItems = Navigation::rootItems($themeName)->columns("id, display_name");

        $data = ["" => "Add as a root item"];
        foreach ($rootItems->fetch(true) as $rootItem) {
            $data[$rootItem->id] = "Child of {$rootItem->display_name}";
        }
        echo json_encode(["counter" => count($data), "status" => "success", "data" => $data]);
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

    public function navigationSearch(?array $data = []): void
    {
        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            $json['redirect'] = $this->router->route("champs.admin.navigationSearchGet", ["search" => $s, "page" => 1]);
            echo json_encode($json);
            return;
        }
        $json['redirect'] = $this->router->route("champs.admin.navigationSearchGet", ["search" => "all", "page" => 1]);
        echo json_encode($json);
        return;

    }

    public function navigationList(?array $data = null): void
    {
        $navigations = (new Navigation())->order("theme_name ASC, display_name ASC");

        $search = null;
        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
            $search = str_search($data["search"]);
            $navigations->where("MATCH(theme_name, display_name) AGAINST(:s)", "s={$search}");
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

        $themeNames = [];
        foreach ((new Navigation())->columns('DISTINCT (m.theme_name)')->fetch(true) as $themeName) {
            $themeNames[] = $themeName->theme_name;
        }

        echo $this->view->render("widgets/navigation/list", [
            "seo" => $seo,
            "title" => $this->title,
            "router" => $this->router,
            "theme_names" => $themeNames,
            "navbar" => $this->navbar,
            "navigations" => $navigations,
            "pager" => $pager
        ]);
    }

    public function navigationCreate(?array $data = null): void
    {
//        $themeNames = [];
//        foreach ((new Navigation())->columns('DISTINCT (m.theme_name)')->fetch(true) as $themeName) {
//            $themeNames[] = $themeName->theme_name;
//        }

        $json['modalFormBS5']['form'] = $this->view->render("widgets/navigation/modal_create", [
            "router" => $this->router,
            "theme_names" => Navigation::availableThemes(),
            "root_items" => Navigation::rootItems(),
        ]);
        echo json_encode($json);
        return;
    }

    public function navigationSave(?array $data = null): void
    {
        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
        $validator = new NavigationValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $navigation = (new Navigation());
        $navigation->fill($data);
        if (!$navigation->save()) {
            var_dump($navigation);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function navigationEdit(?array $data = null): void
    {
        $navigation = (new Navigation())->findById($data['id']);
        $navSequences = (new Navigation())->filteredByThemeName($navigation->theme_name)->order("sequence ASC");
        if ($navigation->parent_id > 0) {
            $navSequences->where("parent_id=:parent_id", "parent_id={$navigation->parent_id}");
        } else {
            $navSequences->where("parent_id IS NULL");
        }

        $json['modalFormBS5']['form'] = $this->view->render("widgets/navigation/modal_edit", [
            "router" => $this->router,
            "navigation" => $navigation,
            "theme_names" => Navigation::availableThemes(),
            "root_items" => Navigation::rootItems($navigation->theme_name),
            "sequences" => $navSequences
        ]);
        echo json_encode($json);
        return;
    }

    public function navigationUpdate(?array $data = null): void
    {
        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
        $validator = new NavigationValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $navigation = (new Navigation())->findById($data['id']);
        $navigation->fill($data);
        $navigation->save();
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function navigationDelete(?array $data = null): void
    {
        /* faz as validações */

        $navigation = (new Navigation())->findById($data['id']);
        $parent_id = $navigation->parent_id;
        $theme_name = $navigation->theme_name;
        if (!$navigation->destroy()) {
            var_dump($navigation);
            die();
        }
        Navigation::reorganize($theme_name, $parent_id);
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function navigationFilterRoot(?array $data = null)
    {
        $themeName = isset($data['theme_name']) ? filter_var($data['theme_name'], FILTER_SANITIZE_STRING) : null;
        $rootItems = Navigation::rootItems($themeName)->columns("id, display_name");

        $data = ["" => "Add as a root item"];
        foreach ($rootItems->fetch(true) as $rootItem) {
            $data[$rootItem->id] = "Child of {$rootItem->display_name}";
        }
        echo json_encode(["counter" => count($data), "status" => "success", "data" => $data]);
    }

    /***********************
     * PARAMETERS
     ***********************/

    public function parametersHome(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        $parameters = [
            /* system */
            "CHAMPS_ENVIRONMENT_IDENTIFIER" => [
                "section" => "system",
                /* text, email, password, select, switch */
                "type" => "select",
                "help_message" => "Define the environment where the app is current running",
                "possible_values" => ["Development" => "DEV", "Tests" => "UAT", "Production" => "PRD"],
                "value" => CHAMPS_ENVIRONMENT_IDENTIFIER,
                "default_value" => ''
            ],
            "CHAMPS_SESSION_NAME" => [
                "section" => "system",
                "type" => "text",
                "help_message" => "Define an unique session name for application and avoid unlike session sharing!",
                "possible_values" => [],
                "value" => CHAMPS_SESSION_NAME,
                "default_value" => '',
            ],
            "CHAMPS_URL_PRD" => [
                "section" => "system",
                "type" => "text",
                "help_message" => "Enter the URL of application running in PRODUCTION environment!",
                "possible_values" => [],
                "value" => defined('CHAMPS_URL_PRD') ? CHAMPS_URL_PRD : '',
                "default_value" => '',
            ],
            "CHAMPS_URL_UAT" => [
                "section" => "system",
                "type" => "text",
                "help_message" => "Enter the URL of application running in TESTS environment!",
                "possible_values" => [],
                "value" => defined('CHAMPS_URL_UAT') ? CHAMPS_URL_UAT : '',
                "default_value" => '',
            ],
            "CHAMPS_URL_DEV" => [
                "section" => "system",
                "type" => "text",
                "help_message" => "Enter the URL of application running in DEVELOPMENT environment!",
                "possible_values" => [],
                "value" => defined('CHAMPS_URL_DEV') ? CHAMPS_URL_DEV : '',
                "default_value" => '',
            ],
            /*
             * STORAGE
             */
            "CHAMPS_STORAGE_ROOT_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of root storage folder. All the other folders will be created there!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_ROOT_FOLDER,
                "default_value" => '',
            ],
            "CHAMPS_STORAGE_TEMPORARY_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of TEMPORARY files!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_TEMPORARY_FOLDER,
                "default_value" => '',
            ],
            "CHAMPS_STORAGE_LOG_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of LOG files!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_LOG_FOLDER,
                "default_value" => '',
            ],
            "CHAMPS_STORAGE_UPLOAD_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of UPLOAD files!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_UPLOAD_FOLDER,
                "default_value" => '',
            ],
            "CHAMPS_STORAGE_IMAGE_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of IMAGES files!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_IMAGE_FOLDER,
                "default_value" => '',
            ],
            "CHAMPS_STORAGE_MEDIA_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of MEDIA files!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_MEDIA_FOLDER,
                "default_value" => '',
            ],
            "CHAMPS_STORAGE_FILE_FOLDER" => [
                "section" => "system storage",
                "type" => "text",
                "help_message" => "Enter folder name of DOCUMENT files!",
                "possible_values" => [],
                "value" => CHAMPS_STORAGE_FILE_FOLDER,
                "default_value" => '',
            ],
            /*
             * LEGACY SUPPORT
             */
            "CHAMPS_SYS_LEGACY_SUPPORT" => [
                "section" => "system legacy support",
                "type" => "switch",
                "help_message" => "legacy!",
                "possible_values" => [],
                "value" => CHAMPS_SYS_LEGACY_SUPPORT,
                "default_value" => '',
            ],
            "CHAMPS_SYS_LEGACY_ROUTE_GROUP" => [
                "section" => "system legacy support",
                "type" => "text",
                "help_message" => "Enter the URL of application running in DEVELOPMENT environment!",
                "possible_values" => [],
                "value" => CHAMPS_SYS_LEGACY_ROUTE_GROUP,
                "default_value" => '',
            ],
            "CHAMPS_SYS_LEGACY_HANDLER" => [
                "section" => "system legacy support",
                "type" => "text",
                "help_message" => "Enter the URL of application running in DEVELOPMENT environment!",
                "possible_values" => [],
                "value" => CHAMPS_SYS_LEGACY_HANDLER,
                "default_value" => '',
            ],
            "CHAMPS_SYS_LEGACY_HANDLER_ACTION" => [
                "section" => "system legacy support",
                "type" => "text",
                "help_message" => "Enter the URL of application running in DEVELOPMENT environment!",
                "possible_values" => [],
                "value" => CHAMPS_SYS_LEGACY_HANDLER_ACTION,
                "default_value" => '',
            ],
            "CHAMPS_FRAMEWORK_CREATE_EXAMPLE_THEME" => [
                "section" => "system",
                "type" => "select",
                "help_message" => "teste help",
                "possible_values" => [
                    "Yes" => true,
                    "No" => false,
                ],
                "value" => true,
                "default_value" => true
            ],
            "nome" => [
                "section" => "system",
                /* text, email, password, select, switch */
                "type" => "switch",
                "help_message" => "teste help",
                "possible_values" => [],
                "value" => false,
                "default_value" => ''
            ],
        ];

        echo $this->view->render("widgets/parameters/home", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
            "parameters" => $parameters
        ]);
    }
}