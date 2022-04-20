<?php
ob_start();
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

require __DIR__ . "/assets/config.php";
require __DIR__ . "/Source/Boot/Constants.php";
//require __DIR__ . "/Source/App/Controlador.php";

//$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

/**
 * BOOTSTRAP
 */

$session = new \BetoCampoy\ChampsFramework\Session();
define("BASE", "https://www.localhost/projetos/repositorios/champs_framework/champs_controller/example");

$router = new \BetoCampoy\ChampsFramework\Router\Router(BASE);
$router->namespace("Source\App");

/**
 * ROUTES
 */
$router->group(null);
$router->get("/", function (){
    echo "<h1>Teste</h1>";
}, "teste");
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