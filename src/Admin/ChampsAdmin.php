<?php


namespace BetoCampoy\ChampsFramework\Admin;


use BetoCampoy\ChampsFramework\Controller\Controller;
use BetoCampoy\ChampsFramework\Models\Auth\AccessLevel;
use BetoCampoy\ChampsFramework\Models\Auth\Permission;
use BetoCampoy\ChampsFramework\Models\Auth\Role;
use BetoCampoy\ChampsFramework\Models\Auth\User;
use BetoCampoy\ChampsFramework\Models\Navigation;
use BetoCampoy\ChampsFramework\Navbar\Navbar;
use BetoCampoy\ChampsFramework\Navbar\Templates\Bootstrap5;
use BetoCampoy\ChampsFramework\Pagination;
use BetoCampoy\ChampsFramework\Parameters\Definer;
use BetoCampoy\ChampsFramework\Parameters\ParameterConfigFile;
use BetoCampoy\ChampsFramework\Router\Router;
use BetoCampoy\ChampsFramework\Support\Validator\Validators\DbAliasValidator;
use BetoCampoy\ChampsFramework\Support\Validator\Validators\DbConnectionValidator;
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
            ->setRootItem("Database", "/champsframework/databases")
            ->setRootItem("Authentication", "/champsframework/auth")
            ->setRootItem("Navigation", "/champsframework/navigation")
            ->setRootItem("Parameters", "/champsframework/parameters")
            ->setRootItem("Reports", "/champsframework/reports");

        $authEntityExists = (new User())->entityExists();
        if ($authEntityExists && (!\user())) {
            $this->router->redirect("login.form");
        }
        elseif ($authEntityExists && (!hasPermission("admin panel list"))) {
            /* non authorized access */
            redirect(url(CHAMPS_SECURITY_FORBIDDEN_ROUTE));
        }
        elseif (!$authEntityExists) {
            if($this->router->current()->method == 'GET'
                && !session()->has("masterAdmin") && current_url() != '/champsframework/login'){
                /* Authentication not activated - show login offline form */

                $this->loginForm();
                die();
            }else{
                $credentials = ["email" => CHAMPS_CONFIG_MASTER_ADMIN_EMAIL, "password" => CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD];
                if (session()->has("masterAdmin") && (array)session()->masterAdmin != $credentials) {
                    /* Authentication not activated - show login offline form */
                    User::logout();
                    $this->router->redirect("login.form");
                }
            }
        }
    }

    /*******************************
     * LOGIN
     ******************************/

    public function loginForm(?array $data = null): void
    {
//        $usrLogged = $route = $this->validation();
//        if ($usrLogged['logged']) {
//            $this->router->redirect($usrLogged['route']);
//        }

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
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

//        $credentials = __get_framework_parameter('CHAMPS_CONFIG_MASTER_ADMIN_USER');
        if ($email != CHAMPS_CONFIG_MASTER_ADMIN_EMAIL) {
            $json['message'] = $this->message->error("The e-mail is invalid!")->render();
            echo json_encode($json);
            return;
        }

        if (!password_verify($password, CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD)) {
            $json['message'] = $this->message->error("Password invalid!")->render();
            echo json_encode($json);
            return;
        }

        session()->set("masterAdmin", ["email" => CHAMPS_CONFIG_MASTER_ADMIN_EMAIL, "password" => CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD]);
        $json['redirect'] = $this->router->route("champs.admin.home");
        echo json_encode($json);
    }

    protected function validation(): array
    {
        $authEntityExists = (new User())->entityExists();
        if (!$authEntityExists && !session()->has("masterAdmin")) {
            /* Authentication not activated - show login offline form */
            return ["logged" => false, "route" => $this->router->route("champs.admin.loginForm")];
        }

        $credentials = ["email" => CHAMPS_CONFIG_MASTER_ADMIN_EMAIL, "password" => CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD];

        if (!$authEntityExists && session()->has("masterAdmin") && (array)session()->masterAdmin != $credentials) {
            /* Authentication not activated - show login offline form */
            User::logout();
            return ["logged" => false, "route" => $this->router->route("champs.admin.loginForm")];
        }

        if ($authEntityExists && (!hasPermission("admin panel list"))) {
            /* non authorized access */
            redirect(url(CHAMPS_SECURITY_FORBIDDEN_ROUTE));
        }

//        if ($authEntityExists && (!\user() || !is_admin())) {
//            /* non authorized access */
//            User::logout();
//            return ["logged" => false, "route" => $this->router->route("login.form")];
//        }
        return ["logged" => true, "route" => null];
    }

    public function home(?array $data = null): void
    {
        $usrLogged = $this->validation();
        if (!$usrLogged['logged']) {
            $this->router->redirect($usrLogged['route']);
        }

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
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
     * DATABASE
     ******************************/

    public function databasesHome(?array $data = null): void
    {
        $seo = $this->seo->render(
            "Manage Database Connections",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/databases/home", [
            "title" => "Manage Database Connections",
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
        ]);
    }

    /* CONNECTIONS */

    public function databasesConnectionList(?array $data = null): void
    {

        $seo = $this->seo->render(
            "Database Connections",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/databases/connections-list", [
            "title" => "Database Connections",
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
            "connections" => __get_framework_db_connections('connections')
        ]);
    }

    public function databasesConnectionCreate(?array $data = null): void
    {
        $json['modalFormBS5']['form'] = $this->view->render("widgets/databases/modal_connection_create", [
            "router" => $this->router,
        ]);
        echo json_encode($json);
        return;
    }

    public function databasesConnectionSave(?array $data = null): void
    {
        $validator = new DbConnectionValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $connections = __get_framework_db_connections('connections');

        $connName = strtoupper(str_slug($data['name']));
        $dbname = $data['dbname'];
        $dbuser = $data['dbuser'];
        $dbpass = $data['dbpass'];
        $dbhost = $data['dbhost'];
        $dbport = $data['dbport'];

        if (isset($connections[$connName])) {
            $json['message'] = $this->message->error("This connection name [{$connName}] has already in use!")->render();
            echo json_encode($json);
            return;
        }

        $connections[$connName] = [
            "dbname" => $dbname,
            "dbuser" => $dbuser,
            "dbpass" => $dbpass,
            "dbhost" => $dbhost,
            "dbport" => $dbport,
        ];

        ksort($connections);

        if (!__set_framework_db_connections("connections", $connections )) {
            var_dump($connections);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function databasesConnectionEdit(?array $data = null): void
    {
        $connName = $data['id'];
        $connections = __get_framework_db_connections("connections");

        if (!isset($connections[$connName])) {
            $json['message'] = $this->message->error("The connection [{$connName}] doesn't exist!")->render();
            echo json_encode($json);
            return;
        }

        $json['modalFormBS5']['form'] = $this->view->render("widgets/databases/modal_connection_edit", [
            "router" => $this->router,
            "connection_name" => $connName,
            "connection_params" => $connections[$connName],
        ]);
        echo json_encode($json);
        return;
    }

    public function databasesConnectionUpdate(?array $data = null): void
    {
        $validator = new DbConnectionValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $connNameOld = $data['id'];
        $connNewName = strtoupper(str_slug($data['name']));
        $connections = __get_framework_db_connections("connections");

        if (!isset($connections[$connNameOld])) {
            $json['message'] = $this->message->error("The connection [{$connNameOld}] doesn't exist!")->render();
            echo json_encode($json);
            return;
        }

        $inUse = array_search_recursive($connNameOld, __get_framework_db_connections('aliases'));
        if ($inUse !== false && $connNameOld != $connNewName) {
            $json['message'] = $this->message->error("It is not possible rename this connection, because it is link into some alias!")->render();
            echo json_encode($json);
            return;
        }

        unset($connections[$connNameOld]);

        $dbname = $data['dbname'];
        $dbuser = $data['dbuser'];
        $dbpass = $data['dbpass'];
        $dbhost = $data['dbhost'];
        $dbport = $data['dbport'];

        $connections[$connNewName] = [
            "dbname" => $dbname,
            "dbuser" => $dbuser,
            "dbpass" => $dbpass,
            "dbhost" => $dbhost,
            "dbport" => $dbport,
        ];

        ksort($connections);

        if (!__set_framework_db_connections("connections", $connections)) {
            var_dump($connections);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;
    }

    public function databasesConnectionDelete(?array $data = null): void
    {
        $connNameOld = $data['id'];
        $connections = __get_framework_db_connections("connections");

        if (!isset($connections[$connNameOld])) {
            $json['message'] = $this->message->error("The connection [{$connNameOld}] doesn't exist!")->render();
            echo json_encode($json);
            return;
        }

        $inUse = array_search_recursive($connNameOld, __get_framework_db_connections('aliases'));
        if ($inUse !== false) {
            $json['message'] = $this->message->error("Fail to delete connection because it is link into some alias!")->render();
            echo json_encode($json);
            return;
        }

        unset($connections[$connNameOld]);

        ksort($connections);

        if (!__set_framework_db_connections("connections", $connections)) {
            var_dump($connections);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    /* ALIASES */

    public function databasesAliasesList(?array $data = null): void
    {

        $seo = $this->seo->render(
            "Define Aliases for Connections",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/databases/aliases-list", [
            "title" => "Define Aliases for Connections",
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
            "aliases" => __get_framework_db_connections('aliases')
        ]);
    }

    public function databasesAliasesCreate(?array $data = null): void
    {
        $json['modalFormBS5']['form'] = $this->view->render("widgets/databases/modal_aliases_create", [
            "router" => $this->router,
            "connections" => __get_framework_db_connections('connections'),
            "aliases" => __get_framework_db_connections('aliases'),
        ]);
        echo json_encode($json);
        return;
    }

    public function databasesAliasesSave(?array $data = null): void
    {
        $validator = new DbAliasValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        $aliases = __get_framework_db_connections('aliases');
        $connections = __get_framework_db_connections('connections');

        $environment = strtoupper($data['environment']);
        $alias = strtolower($data['alias']);
        $connName = strtoupper($data['connection']);

        if (isset($aliases[$environment][$alias])) {
            $json['message'] = $this->message->error("This alias [{$connName}] has already in use, 
            delete it before redefine!")->render();
            echo json_encode($json);
            return;
        }

        $aliases[$environment][$alias] = $connName;

        if (!__set_framework_db_connections("aliases", $aliases )) {
            var_dump($connections);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    public function databasesAliasesEdit(?array $data = null): void
    {
        list($environment, $alias) = explode('-', $data['id']);
        $aliases = __get_framework_db_connections("aliases");

        if (!isset($aliases[$environment][$alias])) {
            $json['message'] = $this->message->error("The aliase [{$alias}] 
            doesn't exist into [{$environment}] environment!")->render();
            echo json_encode($json);
            return;
        }

        $json['modalFormBS5']['form'] = $this->view->render("widgets/databases/modal_aliases_edit", [
            "router" => $this->router,
            "alias" => $alias,
            "environment" => $environment,
            "connection" => $aliases[$environment][$alias],
            "connections" => __get_framework_db_connections('connections'),
        ]);
        echo json_encode($json);
        return;
    }

    public function databasesAliasesUpdate(?array $data = null): void
    {
        $validator = new DbAliasValidator($data);
        $validation = $validator->make();
        $validation->validate();

        if ($errors = $validator->errors($validation)) {
            $json['message'] = $this->message->error($errors)->render();
            echo json_encode($json);
            return;
        }

        list($oldEnvironment, $oldAlias) = explode('-', $data['id']);
        $aliases = __get_framework_db_connections("aliases");

        if (!isset($aliases[$oldEnvironment][$oldAlias])) {
            $json['message'] = $this->message->error("The alias [{$oldAlias}] doesn't
             exist into [{$oldEnvironment}] environment!")->render();
            echo json_encode($json);
            return;
        }

        unset($aliases[$oldEnvironment][$oldAlias]);

        $environment = strtoupper($data['environment']);
        $alias = strtolower($data['alias']);
        $connName = strtoupper($data['connection']);

        $aliases[$environment][$alias] = $connName;

        if (!__set_framework_db_connections("aliases", $aliases)) {
            var_dump($aliases);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;
    }

    public function databasesAliasesDelete(?array $data = null): void
    {
        list($oldEnvironment, $oldAlias) = explode('-', $data['id']);
        $aliases = __get_framework_db_connections("aliases");

        if (!isset($aliases[$oldEnvironment][$oldAlias])) {
            $json['message'] = $this->message->error("The alias [{$oldAlias}] doesn't
             exist into [{$oldEnvironment}] environment!")->render();
            echo json_encode($json);
            return;
        }

        unset($aliases[$oldEnvironment][$oldAlias]);

        if (!__set_framework_db_connections("aliases", $aliases)) {
            var_dump($aliases);
            die();
        }
        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

    /*******************************
     * AUTHENTICATION
     ******************************/

    public function authHome(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
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

    public function permissionsSearch(?array $data = []): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions list");

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
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions list");

        $permissions = (new Permission());

        if(!$permissions->entityExists()){
            $this->message->error("The database table not found. Make sure to create it before continue.
            Check documentation for more information")->flash();
            $this->router->redirect("champs.admin.authHome");
        }

        $page = !empty($data["page"]) ? $data["page"] : 1;
        $pager = new Pagination($this->router->route("champs.admin.permissionsPager"));
        $totalCounter = $permissions->count();
        $pager->pager($totalCounter, 10, $page, 2);
        $permissions->limit($pager->limit())->offset($pager->offset())->order("name DESC");

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/auth/permissions-list", [
            "seo" => $seo,
            "title" => $this->title,
            "router" => $this->router,
            "navbar" => $this->navbar,
            "permissions" => $permissions->order("name ASC"),
            "pager" => $pager
        ]);
    }

    public function permissionsCreate(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions create");

        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_create", [
            "router" => $this->router,
        ]);
        echo json_encode($json);
        return;
    }

    public function permissionsSave(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions create");

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
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions update");

        $permission = (new Permission())->findById($data['id']);

        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_edit", [
            "router" => $this->router,
            "permission" => $permission,
        ]);
        echo json_encode($json);
        return;
    }

    public function permissionsUpdate(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions update");

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
        /* check if user has access */
        hasPermissionRedirectIfFail("permissions delete");

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

    /*******************************
     * AUTHENTICATION - ROLES
     ******************************/

    public function rolesSearch(?array $data = []): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles list");

        //search redirect
        if (!empty($data["s"])) {
            $s = str_search($data["s"]);
            $json['redirect'] = $this->router->route("champs.admin.rolesSearchGet", ["search" => $s, "page" => 1]);
            echo json_encode($json);
            return;
        }
        $json['redirect'] = $this->router->route("champs.admin.rolesSearchGet", ["search" => "all", "page" => 1]);
        echo json_encode($json);
        return;

    }

    public function rolesList(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles list");

        $roles = (new Role());
        if(!$roles->entityExists()){
            $this->message->error("The database table not found. Make sure to create it before continue.
            Check documentation for more information")->flash();
            $this->router->redirect("champs.admin.authHome");
        }

        $page = !empty($data["page"]) ? $data["page"] : 1;
        $pager = new Pagination($this->router->route("champs.admin.rolesPager"));
        $totalCounter = $roles->count();
        $pager->pager($totalCounter, 10, $page, 2);
        $roles->limit($pager->limit())->offset($pager->offset())->order("m.name DESC");

        $seo = $this->seo->render(
            "Manage Roles",
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        echo $this->view->render("widgets/auth/roles-list", [
            "seo" => $seo,
            "title" => "Manage Roles",
            "router" => $this->router,
            "navbar" => $this->navbar,
            "roles" => $roles->order("m.name ASC"),
            "pager" => $pager
        ]);
    }

    public function rolesCreate(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles create");

        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/roles_modal_create", [
            "title" => "Create a New Role",
            "router" => $this->router,
            "accessLevels" => (new AccessLevel())->filteredDataByAuthUser()
        ]);
        echo json_encode($json);
        return;
    }

    public function rolesSave(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles create");

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

    public function rolesEdit(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles update");

        $permission = (new Permission())->findById($data['id']);

        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_edit", [
            "router" => $this->router,
            "permission" => $permission,
        ]);
        echo json_encode($json);
        return;
    }

    public function rolesUpdate(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles update");

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

    public function rolesDelete(?array $data = null): void
    {
        /* check if user has access */
        hasPermissionRedirectIfFail("roles delete");

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

    public function rolesFilterRoot(?array $data = null)
    {
        $themeName = isset($data['theme_name']) ? filter_var($data['theme_name'], FILTER_SANITIZE_STRING) : null;
        $rootItems = Navigation::rootItems($themeName)->columns("id, display_name");

        $data = ["" => "Add as a root item"];
        foreach ($rootItems->fetch(true) as $rootItem) {
            $data[$rootItem->id] = "Child of {$rootItem->display_name}";
        }
        echo json_encode(["counter" => count($data), "status" => "success", "data" => $data]);
    }

    /*******************************
     * AUTHENTICATION - USERS
     ******************************/

//    public function usersSearch(?array $data = []): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users list");
//
//        //search redirect
//        if (!empty($data["s"])) {
//            $s = str_search($data["s"]);
//            $json['redirect'] = $this->router->route("champs.admin.usersSearchGet", ["search" => $s, "page" => 1]);
//            echo json_encode($json);
//            return;
//        }
//        $json['redirect'] = $this->router->route("champs.admin.usersSearchGet", ["search" => "all", "page" => 1]);
//        echo json_encode($json);
//        return;
//
//    }
//
//    public function usersList(?array $data = null): void
//    {
//        $users = (new User());
//        if(!$users->entityExists()){
//            $this->message->error("The database table not found. Make sure to create it before continue.
//            Check documentation for more information")->flash();
//            $this->redirect($this->router->route("champs.admin.authHome"));
//        }
//
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users list");
//
//        $search = null;
//        if (!empty($data["search"]) && str_search($data["search"]) != "all") {
//            $search = str_search($data["search"]);
//            $users->where("MATCH(m.name, m.last_name, m.email) AGAINST(:s)", "s={$search}");
//            if (!$users->count()) {
//                $this->message->info(champs_messages("registers_not_found_in_model"))->flash();
//                $this->router->redirect($this->router->route("champs.admin.usersList"));
//            }
//        }
//
//        $all = ($search ?? "all");
//        $page = !empty($data["page"]) ? $data["page"] : 1;
//        $pager = new Pager($this->router->route("champs.admin.usersPager"));
//        $totalCounter = $users->count();
//        $pager->pager($totalCounter, 10, $page, 2);
//        $users->limit($pager->limit())->offset($pager->offset())->order("m.name DESC");
//
//        $seo = $this->seo->render(
//            "Manage Users",
//            CHAMPS_SEO_SITE_DESCRIPTION,
//            url(current_url()),
//            __champsadm_theme("/assets/images/favicon.ico?123"),
//            false
//        );
//
//        echo $this->view->render("widgets/auth/users-list", [
//            "seo" => $seo,
//            "title" => "Manage Users",
//            "router" => $this->router,
//            "navbar" => $this->navbar,
//            "users" => $users->order("m.name ASC"),
//            "pager" => $pager
//        ]);
//    }
//
//    public function usersCreate(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users create");
//
//        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_create", [
//            "router" => $this->router,
//        ]);
//        echo json_encode($json);
//        return;
//    }
//
//    public function usersSave(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users create");
//
//        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
//        $validator = new PermissionValidator($data);
//        $validation = $validator->make();
//        $validation->validate();
//
//        if ($errors = $validator->errors($validation)) {
//            $json['message'] = $this->message->error($errors)->render();
//            echo json_encode($json);
//            return;
//        }
//
//        $permission = (new Permission());
//        $permission->fill($data);
//        if (!$permission->save()) {
//            var_dump($permission);
//            die();
//        }
//        $json['reload'] = true;
//        echo json_encode($json);
//        return;
//
//    }
//
//    public function usersEdit(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users update");
//
//        $permission = (new Permission())->findById($data['id']);
//
//        $json['modalFormBS5']['form'] = $this->view->render("widgets/auth/permissions_modal_edit", [
//            "router" => $this->router,
//            "permission" => $permission,
//        ]);
//        echo json_encode($json);
//        return;
//    }
//
//    public function usersUpdate(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users update");
//
//        if (isset($data['parent_id']) && empty($data['parent_id'])) unset($data['parent_id']);
//        $validator = new NavigationValidator($data);
//        $validation = $validator->make();
//        $validation->validate();
//
//        if ($errors = $validator->errors($validation)) {
//            $json['message'] = $this->message->error($errors)->render();
//            echo json_encode($json);
//            return;
//        }
//
//        $navigation = (new Navigation())->findById($data['id']);
//        $navigation->fill($data);
//        $navigation->save();
//        $json['reload'] = true;
//        echo json_encode($json);
//        return;
//
//    }
//
//    public function usersDelete(?array $data = null): void
//    {
//        /* check if user has access */
//        hasPermissionRedirectIfFail("users delete");
//
//        /* faz as validações */
//
//        $permission = (new Permission())->findById($data['id']);
//        $name = $permission->name;
//        if (!$permission->destroy()) {
//            var_dump($permission);
//            die();
//        }
//        $this->message->success("The item {$name} deleted from database")->flash();
//        $json['reload'] = true;
//        echo json_encode($json);
//        return;
//
//    }
//
//    public function usersFilterRoot(?array $data = null)
//    {
//        $themeName = isset($data['theme_name']) ? filter_var($data['theme_name'], FILTER_SANITIZE_STRING) : null;
//        $rootItems = Navigation::rootItems($themeName)->columns("id, display_name");
//
//        $data = ["" => "Add as a root item"];
//        foreach ($rootItems->fetch(true) as $rootItem) {
//            $data[$rootItem->id] = "Child of {$rootItem->display_name}";
//        }
//        echo json_encode(["counter" => count($data), "status" => "success", "data" => $data]);
//    }

    /***********************
     * NAVIGATION
     ***********************/

    public function navigationHome(?array $data = null): void
    {
        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
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
        $pager = new Pagination($this->router->route("champs.admin.navigationPager"));
        $totalCounter = $navigations->count();
        $pager->pager($totalCounter, CHAMPS_PAGINATION_LIMIT, $page, CHAMPS_PAGINATION_RANGE);
        $navigations->limit($pager->limit())->offset($pager->offset())->order("display_name DESC");

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
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
        $section = filter_input(INPUT_GET, 'section', FILTER_SANITIZE_STRING);

        $cfgFile = new ParameterConfigFile(__CHAMPS_CONFIG_FILE__);
        $definer = (new Definer($cfgFile));

        $seo = $this->seo->render(
            $this->title,
            CHAMPS_SEO_SITE_DESCRIPTION,
            url(current_url()),
            __champsadm_theme("/assets/images/favicon.ico?123"),
            false
        );

        $data = $definer->getParametersFiltered($section);
        $sections = $data['sections'];
        $parameters = $data['parameters'];
        sort($sections);
        ksort($parameters);

        echo $this->view->render("widgets/parameters/home", [
            "title" => $this->title,
            "router" => $this->router,
            "seo" => $seo,
            "navbar" => $this->navbar,
            "sectionSelected" => $section,
            "sections" => $sections,
            "parametersBySection" => $parameters,
        ]);
    }

    public function parametersSave(?array $data = null): void
    {
        $configFile = new ParameterConfigFile(__CHAMPS_CONFIG_FILE__);
        $definer = (new Definer($configFile))->save($data);

        $json['reload'] = true;
        echo json_encode($json);
        return;

    }

}