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
    public function resource(string $resourceName, $handler, string $name = null, string $modelIdName = null): void
    {
        $resourceName = substr($resourceName, 0, 1) == '/' ? $resourceName : "/".$resourceName;
        $sanitRoute = substr_replace($resourceName, '', 0, 1);
        $sanitRoute = (explode('/', $sanitRoute))[0];
        $modelIdName = $modelIdName ?? singularize($sanitRoute)."_id";

        $this->addRoute("GET", $resourceName, $handler.":list", ($name ? "{$name}.list" : null));
        $this->addRoute("GET", "{$resourceName}/home", $handler.":list", ($name ? "{$name}.home" : null));
        $this->addRoute("GET", "{$resourceName}/home/{search}/{page}", $handler.":list", ($name ? "{$name}.searchGet" : null));
        $this->addRoute("GET", singularize($resourceName), $handler.":create", ($name ? "{$name}.create" : null));
        $this->addRoute("GET", singularize($resourceName)."/{{$modelIdName}}", $handler.":edit", ($name ? "{$name}.edit" : null));
        $this->addRoute("POST", "{$resourceName}/search", $handler.":search", ($name ? "{$name}.searchPost" : null));
        $this->addRoute("POST", singularize($resourceName), $handler.":store", ($name ? "{$name}.store" : null));
        $this->addRoute("POST", singularize($resourceName)."/{{$modelIdName}}", $handler.":update", ($name ? "{$name}.update" : null));
        $this->addRoute("DELETE", singularize($resourceName)."/{{$modelIdName}}", $handler.":delete", ($name ? "{$name}.delete" : null));
    }
}