<?php
ob_start();
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

require __DIR__ . "/assets/config.php";

/*
 * only for test porpouses, in production classes will be loaded by PSR-4 Composer Autoloader
 */

/* load controlers */
require __DIR__ . "/Source/Controllers/Web.php";
/* load models */

/**
 * BOOTSTRAP
 */

$session = new \BetoCampoy\ChampsFramework\Session();

$router = new \BetoCampoy\ChampsFramework\Router\Router(url());
$router->namespace("Source\Controllers");

/**
 * ROUTES
 */
$router->group(null);
$router->get("/", "Web:list", "web.list");

$router->get("/users", "Users:home", "users.home");

/**
 * ERROR ROUTES
 */
$router->group("/ops");
$router->get("/{errcode}", "Web:error", "error");

/**
 * ROUTE
 */
$router->dispatch();

/**
 * ERROR REDIRECT
 */
if ($router->error()) {
    $router->redirect("/ops/{$router->error()}");
}

ob_end_flush();