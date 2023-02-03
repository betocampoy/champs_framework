<?php

namespace BetoCampoy\ChampsFramework\Router;

use Closure;
use function ICanBoogie\singularize;

/**
 * Class Router
 *
 * @package BetoCampoy\ChampsFramework\Router
 */
class Router extends Dispatch
{
    /**
     * Router constructor.
     *
     * @param string $projectUrl
     * @param null|string $separator
     */
    public function __construct(string $projectUrl, ?string $separator = ":")
    {
        parent::__construct($projectUrl, $separator);

        $this->systemRoutes();
    }

    public function systemRoutes()
    {
        $oldNameSpace = $this->namespace;

        if (CHAMPS_LEGACY_SUPPORT_ON) {
            $handler = new \ReflectionClass(CHAMPS_LEGACY_SUPPORT_HANDLER);

            if ($handler->inNamespace()) {
                $this->namespace($handler->getNamespaceName());
                $this->group(CHAMPS_LEGACY_SUPPORT_ROUTE_GROUP);
                $this->get("/{page}", "{$handler->getShortName()}:" . CHAMPS_LEGACY_SUPPORT_HANDLER_ACTION);
                $this->post("/{page}", "{$handler->getShortName()}:" . CHAMPS_LEGACY_SUPPORT_HANDLER_ACTION);
            }
        }

        $this->adminRoutes();

        /* add route to generate minified files */
        $this->namespace("BetoCampoy\ChampsFramework\Help");
        $this->group(null);
        $this->get("/", "Web:home", "web.home");
        /* add route to force minification */
        $this->get("/do-minify", "Web:minify", "web.minify");
        /* add route to force minification */
        $this->get("/champs_parameters/{param}", "Web:parameters", "web.parameters");
        /* add route to generate the default initial data for auth infrastructure */
        $this->get("/auth_initial_data", "Web:authInitialData", "web.authInitialData");
        $this->get("/auth_initial_data/{user_key}", "Web:authInitialData", "web.authInitialData");
        $this->get("/auth_initial_data/{user_key}/{password}", "Web:authInitialData", "web.authInitialData");
        /* add route of system in maintenance */
        $this->get("/uhups/maintenance", "Web:maintenance", "default.maintenance");
        $this->get("/uhups/forbidden", "Web:forbidden", "default.forbidden");
        $this->get("/uhups/error/{errcode}", "Web:error", "default.error");
        /* add the framework help document routes */
        $this->get("/champs-docs/", "Web:documentation", "web.documentation");
        $this->get("/champs-docs/{page}", "Web:documentation", "web.documentation");

        if (CHAMPS_AUTH_ROUTES_CREATE) {
            $handler = new \ReflectionClass(CHAMPS_AUTH_CLASS_HANDLER);

            if ($handler->inNamespace()) {
                $this->namespace($handler->getNamespaceName());
                $this->auth($handler->getShortName(), CHAMPS_AUTH_OPTIN_ROUTES_CREATE);
            }
        }

        $this->namespace($oldNameSpace);
    }

    /**
     * @param string $route
     * @param Closure|string $handler
     * @param string|null $name
     * @param array|string|null $middleware
     */
    public function get(
        string $route,
        $handler,
        string $name = null,
        $middleware = null
    ): void
    {
        $this->addRoute("GET", $route, $handler, $name, $middleware);
    }

    /**
     * @param string $route
     * @param Closure|string $handler
     * @param string|null $name
     * @param array|string|null $middleware
     */
    public function post(
        string $route,
        $handler,
        string $name = null,
        $middleware = null
    ): void
    {
        $this->addRoute("POST", $route, $handler, $name, $middleware);
    }

    /**
     * @param string $route
     * @param Closure|string $handler
     * @param string|null $name
     * @param array|string|null $middleware
     */
    public function put(
        string $route,
        $handler,
        string $name = null,
        $middleware = null
    ): void
    {
        $this->addRoute("PUT", $route, $handler, $name, $middleware);
    }

    /**
     * @param string $route
     * @param Closure|string $handler
     * @param string|null $name
     * @param array|string|null $middleware
     */
    public function patch(
        string $route,
        $handler,
        string $name = null,
        $middleware = null
    ): void
    {
        $this->addRoute("PATCH", $route, $handler, $name, $middleware);
    }

    /**
     * @param string $route
     * @param Closure|string $handler
     * @param string|null $name
     * @param array|string|null $middleware
     */
    public function delete(
        string $route,
        $handler,
        string $name = null,
        $middleware = null
    ): void
    {
        $this->addRoute("DELETE", $route, $handler, $name, $middleware);
    }

    /**
     * @param string $resourceRoute
     * @param $handler
     * @param string|null $name
     * @param string|null $modelIdName
     */
    public function resource(string $resourceRoute, $handler, string $name = null, string $modelIdName = null): void
    {
        $resourceRoute = strtolower($resourceRoute[0] == '/' ? $resourceRoute : "/" . $resourceRoute);
        $sanitRoute = substr_replace($resourceRoute, '', 0, 1);
        $sanitRoute = (explode('/', $sanitRoute))[0];
        $modelIdName = $modelIdName ?? singularize($sanitRoute) . "_id";

        $this->addRoute("GET", $resourceRoute, $handler . ":list", ($name ? "{$name}.list" : null));
        $this->addRoute("GET", "{$resourceRoute}/home", $handler . ":list", ($name ? "{$name}.home" : null));
        $this->addRoute("GET", "{$resourceRoute}/home/", $handler . ":list", ($name ? "{$name}.paginatorLink" : null));
//        $this->addRoute("GET", "{$resourceRoute}/home/{search}/", $handler . ":list", ($name ? "{$name}.paginatorLink" : null));
        $this->addRoute("GET", "{$resourceRoute}/home/{page}", $handler . ":list", ($name ? "{$name}.searchGet" : null));
//        $this->addRoute("GET", "{$resourceRoute}/home/{search}/{page}", $handler . ":list", ($name ? "{$name}.searchGet" : null));
        $this->addRoute("GET", singularize($resourceRoute), $handler . ":create", ($name ? "{$name}.create" : null));
        $this->addRoute("GET", singularize($resourceRoute) . "/{{$modelIdName}}", $handler . ":edit", ($name ? "{$name}.edit" : null));
        $this->addRoute("POST", "{$resourceRoute}/search", $handler . ":search", ($name ? "{$name}.searchPost" : null));
        $this->addRoute("POST", singularize($resourceRoute), $handler . ":store", ($name ? "{$name}.store" : null));
        $this->addRoute("POST", singularize($resourceRoute) . "/{{$modelIdName}}", $handler . ":update", ($name ? "{$name}.update" : null));
        $this->addRoute("DELETE", singularize($resourceRoute) . "/{{$modelIdName}}", $handler . ":delete", ($name ? "{$name}.delete" : null));
    }

    protected function adminRoutes(): void
    {
        /* Framework Administrations Default routes */
        $this->namespace("BetoCampoy\ChampsFramework\Admin");
        $this->group(null);
        $this->get("/champsframework", "ChampsAdmin:home", "champs.admin.home");
        $this->get("/champsframework/login", "ChampsAdmin:loginForm", "champs.admin.loginForm");
        $this->post("/champsframework/login", "ChampsAdmin:login", "champs.admin.login");

        /* database */
        $this->get("/champsframework/databases", "ChampsAdmin:databasesHome", "champs.admin.databasesHome");
        /* database - connections */
        $this->get("/champsframework/databases/connections/list", "ChampsAdmin:databasesConnectionList", "champs.admin.databasesConnectionList");
        $this->get("/champsframework/databases/connections/list/{search}/{page}", "ChampsAdmin:databasesConnectionList", "champs.admin.databasesConnectionSearchGet");
        $this->post("/champsframework/databases/connections/search", "ChampsAdmin:databasesConnectionSearch", "champs.admin.databasesConnectionSearch");
        $this->post("/champsframework/databases/connections/create", "ChampsAdmin:databasesConnectionCreate", "champs.admin.databasesConnectionCreate");
        $this->post("/champsframework/databases/connections/save", "ChampsAdmin:databasesConnectionSave", "champs.admin.databasesConnectionSave");
        $this->post("/champsframework/databases/connections/update/{id}", "ChampsAdmin:databasesConnectionEdit", "champs.admin.databasesConnectionEdit");
        $this->post("/champsframework/databases/connections/update/{id}/save", "ChampsAdmin:databasesConnectionUpdate", "champs.admin.databasesConnectionUpdate");
        $this->post("/champsframework/databases/connections/delete/{id}", "ChampsAdmin:databasesConnectionDelete", "champs.admin.databasesConnectionDelete");
        /* database - aliases */
        $this->get("/champsframework/databases/aliases/list", "ChampsAdmin:databasesAliasesList", "champs.admin.databasesAliasesList");
        $this->get("/champsframework/databases/aliases/list/{search}/{page}", "ChampsAdmin:databasesAliasesList", "champs.admin.databasesAliasesSearchGet");
        $this->post("/champsframework/databases/aliases/search", "ChampsAdmin:databasesAliasesSearch", "champs.admin.databasesAliasesSearch");
        $this->post("/champsframework/databases/aliases/create", "ChampsAdmin:databasesAliasesCreate", "champs.admin.databasesAliasesCreate");
        $this->post("/champsframework/databases/aliases/save", "ChampsAdmin:databasesAliasesSave", "champs.admin.databasesAliasesSave");
        $this->post("/champsframework/databases/aliases/update/{id}", "ChampsAdmin:databasesAliasesEdit", "champs.admin.databasesAliasesEdit");
        $this->post("/champsframework/databases/aliases/update/{id}/save", "ChampsAdmin:databasesAliasesUpdate", "champs.admin.databasesAliasesUpdate");
        $this->post("/champsframework/databases/aliases/delete/{id}", "ChampsAdmin:databasesAliasesDelete", "champs.admin.databasesAliasesDelete");


        /* auth */
        $this->get("/champsframework/auth", "ChampsAdmin:authHome", "champs.admin.authHome");

        /* auth - permissions */
        $this->get("/champsframework/auth/permissions", "ChampsAdmin:permissionsList", "champs.admin.permissionsHome");
        $this->get("/champsframework/auth/permissions/list", "ChampsAdmin:permissionsList", "champs.admin.permissionsList");
        $this->get("/champsframework/auth/permissions/list/{search}/", "ChampsAdmin:permissionsList", "champs.admin.permissionsPager");
        $this->get("/champsframework/auth/permissions/list/{search}/{page}", "ChampsAdmin:permissionsList", "champs.admin.permissionsSearchGet");
        $this->post("/champsframework/auth/permissions/search", "ChampsAdmin:permissionsSearch", "champs.admin.permissionsSearch");
        $this->post("/champsframework/auth/permissions/create", "ChampsAdmin:permissionsCreate", "champs.admin.permissionsCreate");
        $this->post("/champsframework/auth/permissions/save", "ChampsAdmin:permissionsSave", "champs.admin.permissionsSave");
        $this->post("/champsframework/auth/permissions/update/{id}", "ChampsAdmin:permissionsEdit", "champs.admin.permissionsEdit");
        $this->post("/champsframework/auth/permissions/update/{id}/save", "ChampsAdmin:permissionsUpdate", "champs.admin.permissionsUpdate");
        $this->post("/champsframework/auth/permissions/delete/{id}", "ChampsAdmin:permissionsDelete", "champs.admin.permissionsDelete");
        $this->post("/champsframework/auth/permissions/filter/root", "ChampsAdmin:permissionsFilterRoot", "champs.admin.permissionsFilterRoot");
        /* auth - roles */
        $this->get("/champsframework/auth/roles", "ChampsAdmin:rolesList", "champs.admin.rolesHome");
        $this->get("/champsframework/auth/roles/list", "ChampsAdmin:rolesList", "champs.admin.rolesList");
        $this->get("/champsframework/auth/roles/list/{search}/", "ChampsAdmin:rolesList", "champs.admin.rolesPager");
        $this->get("/champsframework/auth/roles/list/{search}/{page}", "ChampsAdmin:rolesList", "champs.admin.rolesSearchGet");
        $this->post("/champsframework/auth/roles/search", "ChampsAdmin:rolesSearch", "champs.admin.rolesSearch");
        $this->post("/champsframework/auth/roles/create", "ChampsAdmin:rolesCreate", "champs.admin.rolesCreate");
        $this->post("/champsframework/auth/roles/save", "ChampsAdmin:rolesSave", "champs.admin.rolesSave");
        $this->post("/champsframework/auth/roles/update/{id}", "ChampsAdmin:rolesEdit", "champs.admin.rolesEdit");
        $this->post("/champsframework/auth/roles/update/{id}/save", "ChampsAdmin:rolesUpdate", "champs.admin.rolesUpdate");
        $this->post("/champsframework/auth/roles/delete/{id}", "ChampsAdmin:rolesDelete", "champs.admin.rolesDelete");
        $this->post("/champsframework/auth/roles/filter/root", "ChampsAdmin:rolesFilterRoot", "champs.admin.rolesFilterRoot");
        /* auth - users */
        $this->get("/champsframework/auth/users", "ChampsAdmin:usersList", "champs.admin.usersHome");
        $this->get("/champsframework/auth/users/list", "ChampsAdmin:usersList", "champs.admin.usersList");
        $this->get("/champsframework/auth/users/list/{search}/", "ChampsAdmin:usersList", "champs.admin.usersPager");
        $this->get("/champsframework/auth/users/list/{search}/{page}", "ChampsAdmin:usersList", "champs.admin.usersSearchGet");
        $this->post("/champsframework/auth/users/search", "ChampsAdmin:usersSearch", "champs.admin.usersSearch");
        $this->post("/champsframework/auth/users/create", "ChampsAdmin:usersCreate", "champs.admin.usersCreate");
        $this->post("/champsframework/auth/users/save", "ChampsAdmin:usersSave", "champs.admin.usersSave");
        $this->post("/champsframework/auth/users/update/{id}", "ChampsAdmin:usersEdit", "champs.admin.usersEdit");
        $this->post("/champsframework/auth/users/update/{id}/save", "ChampsAdmin:usersUpdate", "champs.admin.usersUpdate");
        $this->post("/champsframework/auth/users/delete/{id}", "ChampsAdmin:usersDelete", "champs.admin.usersDelete");
        $this->post("/champsframework/auth/users/filter/root", "ChampsAdmin:usersFilterRoot", "champs.admin.usersFilterRoot");

        /* navigation */
        $this->get("/champsframework/navigation", "ChampsAdmin:navigationHome", "champs.admin.navigationHome");
        $this->get("/champsframework/navigation/list", "ChampsAdmin:navigationList", "champs.admin.navigationList");
        $this->get("/champsframework/navigation/list/{search}/", "ChampsAdmin:navigationList", "champs.admin.navigationPager");
        $this->get("/champsframework/navigation/list/{search}/{page}", "ChampsAdmin:navigationList", "champs.admin.navigationSearchGet");
        $this->post("/champsframework/navigation/search", "ChampsAdmin:navigationSearch", "champs.admin.navigationSearch");
        $this->post("/champsframework/navigation/create", "ChampsAdmin:navigationCreate", "champs.admin.navigationCreate");
        $this->post("/champsframework/navigation/save", "ChampsAdmin:navigationSave", "champs.admin.navigationSave");
        $this->post("/champsframework/navigation/update/{id}", "ChampsAdmin:navigationEdit", "champs.admin.navigationEdit");
        $this->post("/champsframework/navigation/update/{id}/save", "ChampsAdmin:navigationUpdate", "champs.admin.navigationUpdate");
        $this->post("/champsframework/navigation/delete/{id}", "ChampsAdmin:navigationDelete", "champs.admin.navigationDelete");
        $this->post("/champsframework/navigation/filter/root", "ChampsAdmin:navigationFilterRoot", "champs.admin.navigationFilterRoot");

        /* parameters */
        $this->get("/champsframework/parameters", "ChampsAdmin:parametersHome", "champs.admin.parametersHome");
        $this->post("/champsframework/parameters", "ChampsAdmin:parametersSave", "champs.admin.parametersSave");
    }

    public function auth(string $handler = null, bool $optin = false)
    {
        $oldNameSpace = null;
        if (!$handler) {
            $oldNameSpace = $this->namespace;
            $this->namespace("BetoCampoy\ChampsFramework\Controller");
            $handler = "AuthController";
        }

        // login root
        $this->addRoute("GET", "/root", "{$handler}:root", "login.root");

        // login
        $this->addRoute("GET", "/login", "{$handler}:loginForm", "login.form");
        $this->addRoute("POST", "/login", "{$handler}:loginExecute", "login");
        $this->addRoute("GET", "/logout", "{$handler}:logout", "logout");

        // forget pass
        $this->addRoute("GET", "/forget", "{$handler}:forgetForm", "forget.form");
        $this->addRoute("POST", "/forget", "{$handler}:forgetExecute", "forget");

        // reset pass
        $this->addRoute("GET", "/reset/{code}", "{$handler}:resetForm", "reset.form");
        $this->addRoute("POST", "/reset/confirm", "{$handler}:resetExecute", "reset");

        // oauth2 callbacks
        if (CHAMPS_AUTH_FACEBOOK_ON) $this->addRoute("GET", "/facebook", "{$handler}:callbackFacebook", "callback.facebook");

        //optin
        if ($optin) {
            // open form to self register a new user
            $this->addRoute("GET", "/optin/register", "{$handler}:registerForm", "register.form");
            // insert new user in database -> send a confirmation email -> show a confirmation message
            $this->addRoute("POST", "/optin/register", "{$handler}:registerExecute", "register");
            // open form with a message asking for user check email to confirm registration
            $this->addRoute("GET", "/optin/confirm", "{$handler}:confirm", "register.confirm");
            // validate user and change user status to confirmed
            $this->addRoute("GET", "/optin/welcome/{email}", "{$handler}:welcome", "register.welcome");
        }

        // dashboard default routes

        $dashHandler = function (?string $namespace, ?string $handler, ?string $action) {
//            $class = $namespace."\\".$handler;
//            var_dump($namespace ,$handler ,$action, $class);
//            var_dump(class_exists("Source\\App\\WebExample"));
            if(!$handler || !$action) return null;

            return "{$handler}:{$action}";
        };
        if (CHAMPS_AUTH_ROUTES_CLI_NAMESPACE) $this->namespace(CHAMPS_AUTH_ROUTES_CLI_NAMESPACE);
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES_CLI
            , ($dashHandler(CHAMPS_AUTH_ROUTES_CLI_NAMESPACE, CHAMPS_AUTH_ROUTES_CLI_HANDLER, CHAMPS_AUTH_ROUTES_CLI_ACTION)) ?? function () {
                echo "Create a user Client controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['client']['handler'] ";
            }
            , "dash.client");
        $this->namespace(CHAMPS_AUTH_ROUTES_OPR_NAMESPACE);
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES_OPR
            , ($dashHandler(CHAMPS_AUTH_ROUTES_OPR_NAMESPACE, CHAMPS_AUTH_ROUTES_OPR_HANDLER, CHAMPS_AUTH_ROUTES_OPR_ACTION)) ?? function () {
                echo "Create a user Operator controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['operator']['handler'] ";
            }
            , "dash.operator");
        $this->namespace(CHAMPS_AUTH_ROUTES_ADM_NAMESPACE);
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES_ADM
            , ($dashHandler(CHAMPS_AUTH_ROUTES_ADM_NAMESPACE, CHAMPS_AUTH_ROUTES_ADM_HANDLER, CHAMPS_AUTH_ROUTES_ADM_ACTION)) ?? function () {
                echo "Create a user Administrator controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['admin']['handler'] ";
            }
            , "dash.admin");

        $this->namespace($oldNameSpace);
    }

}