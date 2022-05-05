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
    ): void {
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
    ): void {
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
    ): void {
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
    ): void {
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
    ): void {
        $this->addRoute("DELETE", $route, $handler, $name, $middleware);
    }

    /**
     * @param string      $resourceName
     * @param             $handler
     * @param string|null $name
     * @param string|null $modelIdName
     */
    public function resource(string $resourceRoute, $handler, string $name = null, string $modelIdName = null): void
    {
        $resourceRoute = strtolower($resourceRoute[0] == '/' ? $resourceRoute : "/".$resourceRoute);
        $sanitRoute = substr_replace($resourceRoute, '', 0, 1);
        $sanitRoute = (explode('/', $sanitRoute))[0];
        $modelIdName = $modelIdName ?? singularize($sanitRoute)."_id";

        $this->addRoute("GET", $resourceRoute, $handler.":list", ($name ? "{$name}.list" : null));
        $this->addRoute("GET", "{$resourceRoute}/home", $handler.":list", ($name ? "{$name}.home" : null));
        $this->addRoute("GET", "{$resourceRoute}/home/{search}/{page}", $handler.":list", ($name ? "{$name}.searchGet" : null));
        $this->addRoute("GET", singularize($resourceRoute), $handler.":create", ($name ? "{$name}.create" : null));
        $this->addRoute("GET", singularize($resourceRoute)."/{{$modelIdName}}", $handler.":edit", ($name ? "{$name}.edit" : null));
        $this->addRoute("POST", "{$resourceRoute}/search", $handler.":search", ($name ? "{$name}.searchPost" : null));
        $this->addRoute("POST", singularize($resourceRoute), $handler.":store", ($name ? "{$name}.store" : null));
        $this->addRoute("POST", singularize($resourceRoute)."/{{$modelIdName}}", $handler.":update", ($name ? "{$name}.update" : null));
        $this->addRoute("DELETE", singularize($resourceRoute)."/{{$modelIdName}}", $handler.":delete", ($name ? "{$name}.delete" : null));
    }

    public function auth(string $handler, bool $optin = false)
    {
        // login
        $this->addRoute("GET", "/login", "{$handler}:loginForm", "login.loginForm");
        $this->addRoute("POST", "/login", "{$handler}:loginExecute", "login.loginExecute");

        // forget pass
        $this->addRoute("GET", "/forget","{$handler}:forgetForm", "login.forgetForm");
        $this->addRoute("POST", "/forget","{$handler}:forgetExecute", "login.forgetExecute");

        // reset pass
        $this->addRoute("GET", "/reset/{code}","{$handler}:resetForm", "login.resetForm");
        $this->addRoute("POST", "/reset/confirm","{$handler}:resetExecute", "login.resetExecute");

        //optin
        if($optin){
            // open form to register a new user
            $this->addRoute("GET", "/register","{$handler}:registerForm", "login.registerForm");
            // insert new user in database
            $this->addRoute("POST", "/register","{$handler}:registerExecute", "login.registerExecute");
            // open form asking user to show email
            $this->addRoute("GET", "/confirm","{$handler}:confirm", "login.confirm");
            // change user status to confirmed
            $this->addRoute("GET", "/welcome/{email}","{$handler}:welcome", "login.welcome");
        }
    }
}