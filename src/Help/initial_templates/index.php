<?php
ob_start();
date_default_timezone_set('America/Sao_Paulo');
require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use BetoCampoy\ChampsFramework\Session;
use BetoCampoy\ChampsFramework\Router\Router;
use function ICanBoogie\pluralize;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * EXAMPLE THEME ROUTES
 */
$route->group(null);
$route->get("/", "WebExample:home");
$route->get("/terms", "WebExample:terms");
$route->get("/contact", "WebExample:contact");

/**
 * CREATE YOUR CUSTOM ROUTES BELOW
 */


/**
 * CREATE YOUR CUSTOM ROUTES ABOVE
 */

/**
 * ROUTE DISPATCH
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect( $route->route("default.error", ["errcode" => $route->error()]));
}

ob_end_flush();