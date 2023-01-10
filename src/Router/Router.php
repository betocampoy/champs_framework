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

        if (CHAMPS_SYS_LEGACY_SUPPORT) {
            $handler = new \ReflectionClass(CHAMPS_SYS_LEGACY_HANDLER);

            if ($handler->inNamespace()) {
                $this->namespace($handler->getNamespaceName());
                $this->group(CHAMPS_SYS_LEGACY_ROUTE_GROUP);
                $this->get("/{page}", "{$handler->getShortName()}:".CHAMPS_SYS_LEGACY_HANDLER_ACTION);
                $this->post("/{page}", "{$handler->getShortName()}:".CHAMPS_SYS_LEGACY_HANDLER_ACTION);
            }
        }

        $this->adminRoutes();

        /* add route to generate minified files */
        $this->namespace("BetoCampoy\ChampsFramework\Help");
        $this->group(null);
        $this->get("/", "Web:home", "web.home");
        /* add route to force minification */
        $this->get("/do-minify", "Web:minify", "web.minify");
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
                $this->auth($handler->getShortName(), CHAMPS_OPTIN_ROUTES_CREATE);
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
     * @param string $resourceName
     * @param             $handler
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
        $this->addRoute("GET", "{$resourceRoute}/home/{search}/", $handler . ":list", ($name ? "{$name}.paginatorLink" : null));
        $this->addRoute("GET", "{$resourceRoute}/home/{search}/{page}", $handler . ":list", ($name ? "{$name}.searchGet" : null));
        $this->addRoute("GET", singularize($resourceRoute), $handler . ":create", ($name ? "{$name}.create" : null));
        $this->addRoute("GET", singularize($resourceRoute) . "/{{$modelIdName}}", $handler . ":edit", ($name ? "{$name}.edit" : null));
        $this->addRoute("POST", "{$resourceRoute}/search", $handler . ":search", ($name ? "{$name}.searchPost" : null));
        $this->addRoute("POST", singularize($resourceRoute), $handler . ":store", ($name ? "{$name}.store" : null));
        $this->addRoute("POST", singularize($resourceRoute) . "/{{$modelIdName}}", $handler . ":update", ($name ? "{$name}.update" : null));
        $this->addRoute("DELETE", singularize($resourceRoute) . "/{{$modelIdName}}", $handler . ":delete", ($name ? "{$name}.delete" : null));
    }

    protected function adminRoutes():void
    {
        /* Framework Administrations Default routes */
        $this->namespace("BetoCampoy\ChampsFramework\Admin");
        $this->group(null);
        $this->get("/champsframework", "ChampsAdmin:home", "champs.admin.home");

        /* navigation */
        $this->get("/champsframework/navigation", "ChampsAdmin:navigationHome", "champs.admin.navigationHome");
        $this->get("/champsframework/navigation/list", "ChampsAdmin:navigationList", "champs.admin.navigationList");
        $this->get("/champsframework/navigation/list/{search}/", "ChampsAdmin:navigationList", "champs.admin.navigationPager");
        $this->get("/champsframework/navigation/list/{search}/{page}", "ChampsAdmin:navigationList", "champs.admin.navigationSearchGet");
        $this->post("/champsframework/navigation/search", "ChampsAdmin:navigationSearch", "champs.admin.navigationSearch");
        $this->post("/champsframework/navigation/create", "ChampsAdmin:navigationCreate", "champs.admin.navigationCreate");
        $this->post("/champsframework/navigation/save", "ChampsAdmin:navigationSave", "champs.admin.navigationSave");
        $this->post("/champsframework/navigation/update/{id}", "ChampsAdmin:navigationEdit", "champs.admin.navigationEdit");
        $this->post("/champsframework/navigation/update/{id}/save","ChampsAdmin:navigationUpdate", "champs.admin.navigationUpdate");
        $this->post("/champsframework/navigation/delete/{id}", "ChampsAdmin:navigationDelete", "champs.admin.navigationDelete");
        $this->post("/champsframework/navigation/filter/root","ChampsAdmin:navigationFilterRoot", "champs.admin.navigationFilterRoot");
    }

    public function auth(string $handler = null, bool $optin = false)
    {
        if (!defined("CHAMPS_AUTH_ROUTES")) {
            throw new \Exception("To implement auth routes, you must define CHAMPS_AUTH_ROUTES constant. 
            Copy the command and paste em boot/constants.php file 
            define(\"CHAMPS_AUTH_ROUTES\", 
            [\"admin\" => [\"route\" => \"/customized_admin_route\", \"handler\" => \"AdminHandlerName:method\" ], 
            \"operator\" => [\"route\" => \"/customized_operator_route\", \"handler\" => \"OperatorHandlerName:method\"],
            \"client\" => [\"route\" => \"/customized_client_route\",\"handler\" => \"ClientHandlerName:method\"]]);");
        }

        if (!isset(CHAMPS_AUTH_ROUTES["client"]) || !isset(CHAMPS_AUTH_ROUTES["operator"]) || !isset(CHAMPS_AUTH_ROUTES["admin"]) ||
            !isset(CHAMPS_AUTH_ROUTES["client"]["route"]) || !isset(CHAMPS_AUTH_ROUTES["operator"]["route"]) || !isset(CHAMPS_AUTH_ROUTES["admin"]["route"])
//         || !isset(CHAMPS_AUTH_ROUTES["client"]["handler"]) || !isset(CHAMPS_AUTH_ROUTES["operator"]["handler"]) || !isset(CHAMPS_AUTH_ROUTES["admin"]["handler"])
        ) {
            throw new \Exception("The constant CHAMPS_AUTH_ROUTES has invalid format. Copy the command and paste em boot/constants.php file 
            define(\"CHAMPS_AUTH_ROUTES\", 
            [\"admin\" => [\"route\" => \"/customized_admin_route\", \"handler\" => \"AdminHandlerName:method\" ], 
            \"operator\" => [\"route\" => \"/customized_operator_route\", \"handler\" => \"OperatorHandlerName:method\"],
            \"client\" => [\"route\" => \"/customized_client_route\",\"handler\" => \"ClientHandlerName:method\"]]);");
        }

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
        if (CHAMPS_OAUTH_FACEBOOK_ENABLE) $this->addRoute("GET", "/facebook", "{$handler}:callbackFacebook", "callback.facebook");

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
        if(CHAMPS_AUTH_ROUTES["client"]['namespace']) $this->namespace(CHAMPS_AUTH_ROUTES["client"]['namespace']);
        $dashHandler = function (?array $dashConfig = []){
            return !empty($dashConfig["handler"]) && !empty($dashConfig["action"])
                ? "{$dashConfig['handler']}:{$dashConfig['action']}"
                : null;
        };
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES["client"]["route"]
            , $dashHandler(CHAMPS_AUTH_ROUTES['client']) ?? function () {
                echo "Create a user Cliente controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['client']['handler'] ";
            }
            , "dash.client");
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES["operator"]["route"]
            , $dashHandler(CHAMPS_AUTH_ROUTES['operator']) ?? function () {
                echo "Create a user Operator controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['operator']['handler'] ";
            }
            , "dash.operator");
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES["admin"]["route"]
            , $dashHandler(CHAMPS_AUTH_ROUTES['admin']) ?? function () {
                echo "Create a user Administrator controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['admin']['handler'] ";
            }
            , "dash.admin");

        $this->namespace($oldNameSpace);
    }

    public function auth_old(string $handler = null, bool $optin = false)
    {
        if (!defined("CHAMPS_AUTH_ROUTES")) {
            throw new \Exception("To implement auth routes, you must define CHAMPS_AUTH_ROUTES constant. 
            Copy the command and paste em boot/constants.php file 
            define(\"CHAMPS_AUTH_ROUTES\", 
            [\"admin\" => [\"route\" => \"/customized_admin_route\", \"handler\" => \"AdminHandlerName:method\" ], 
            \"operator\" => [\"route\" => \"/customized_operator_route\", \"handler\" => \"OperatorHandlerName:method\"],
            \"client\" => [\"route\" => \"/customized_client_route\",\"handler\" => \"ClientHandlerName:method\"]]);");
        }

        if (!isset(CHAMPS_AUTH_ROUTES["client"]) || !isset(CHAMPS_AUTH_ROUTES["operator"]) || !isset(CHAMPS_AUTH_ROUTES["admin"]) ||
            !isset(CHAMPS_AUTH_ROUTES["client"]["route"]) || !isset(CHAMPS_AUTH_ROUTES["operator"]["route"]) || !isset(CHAMPS_AUTH_ROUTES["admin"]["route"])
//         || !isset(CHAMPS_AUTH_ROUTES["client"]["handler"]) || !isset(CHAMPS_AUTH_ROUTES["operator"]["handler"]) || !isset(CHAMPS_AUTH_ROUTES["admin"]["handler"])
        ) {
            throw new \Exception("The constant CHAMPS_AUTH_ROUTES has invalid format. Copy the command and paste em boot/constants.php file 
            define(\"CHAMPS_AUTH_ROUTES\", 
            [\"admin\" => [\"route\" => \"/customized_admin_route\", \"handler\" => \"AdminHandlerName:method\" ], 
            \"operator\" => [\"route\" => \"/customized_operator_route\", \"handler\" => \"OperatorHandlerName:method\"],
            \"client\" => [\"route\" => \"/customized_client_route\",\"handler\" => \"ClientHandlerName:method\"]]);");
        }

        $oldNameSpace = null;
        if (!$handler) {
            $oldNameSpace = $this->namespace;
            $this->namespace("BetoCampoy\ChampsFramework\Controller");
            $handler = "AuthController";
        }

        // dashboard default routes

        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES["client"]["route"]
            , CHAMPS_AUTH_ROUTES["client"]["handler"] ?? function () {
                echo "Create a user Cliente controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['client']['handler'] ";
            }
            , "dash.client");
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES["operator"]["route"]
            , CHAMPS_AUTH_ROUTES["operator"]["handler"] ?? function () {
                echo "Create a user Operator controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['operator']['handler'] ";
            }
            , "dash.operator");
        $this->addRoute("GET"
            , CHAMPS_AUTH_ROUTES["admin"]["route"]
            , CHAMPS_AUTH_ROUTES["admin"]["handler"] ?? function () {
                echo "Create a user Administrator controller to handler access after user login. Register the new handler at CHAMPS_AUTH_ROUTES['admin']['handler'] ";
            }
            , "dash.admin");

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
        if (CHAMPS_OAUTH_FACEBOOK_ENABLE) $this->addRoute("GET", "/facebook", "{$handler}:callbackFacebook", "callback.facebook");

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

        $this->namespace($oldNameSpace);
    }


}