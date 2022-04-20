<?php

namespace BetoCampoy\ChampsFramework\Router;

use Closure;

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
}