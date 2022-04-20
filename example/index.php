<?php
ob_start();
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

require __DIR__ . "/assets/config.php";
//require __DIR__ . "/Source/App/Controlador.php";

//$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

/**
 * BOOTSTRAP
 */

$session = new \BetoCampoy\ChampsFramework\Session();

$router = new \BetoCampoy\ChampsFramework\Router\Router(CHAMPS_URL);
$router->namespace("Source\Controllers");

/**
 * ROUTES
 */
$router->group(null);
$router->get("/", "Web:home", "web.home");
$router->get("/clousure", function (){
    echo "<h1>Teste</h1>";
}, "web.clousure");
//$router->get("/", "Controlador:menu", "controlador.menu");
//$router->get("/teste", "Controlador:list", "controlador.list");
//$router->get("/sao_destroy", "Controlador:destroy", "controlador.destroy");

/**
 * ERROR ROUTES
 */
$router->group("/ops");
$router->get("/{errcode}", "Controlador:error", "erro");

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