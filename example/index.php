<?php

ob_start();
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

require __DIR__ . "/assets/config.php";

$session = new \BetoCampoy\ChampsFramework\Session();

/*
 * only for test porpouses, in production classes will be loaded by PSR-4 Composer Autoloader
 */

/* load controlers */
require __DIR__ . "/Source/Controllers/Web.php";
/* load models */

/**
 * BOOTSTRAP
 */



$router = new \BetoCampoy\ChampsFramework\Router\Router(url());
$router->namespace("Source\Controllers");

/**
 * ROUTES
 */
$router->group(null);
$router->get("/", "Web:home", "web.home");
$router->get("/facebook", "Web:facebook", "web.facebook");
$router->get("/install", "Web:install", "web.install");
$router->get("/boot", "Web:boot", "web.boot");
$router->get("/constants", "Web:constants", "web.constants");
$router->get("/router", "Web:router", "web.router");
$router->get("/controller", "Web:controller", "web.controller");
$router->get("/model", "Web:model", "web.model");
$router->get("/session", "Web:session", "web.session");
$router->get("/authentication", "Web:authentication", "web.authentication");
$router->get("/validation", "Web:validation", "web.validation");
$router->get("/csrf", "Web:csrf", "web.csrf");
$router->get("/navigation", "Web:navigation", "web.navigation");
$router->get("/seo", "Web:seo", "web.seo");
$router->get("/messages", "Web:messages", "web.messages");
$router->get("/jqueryengine", "Web:jqueryengine", "web.jqueryengine");









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