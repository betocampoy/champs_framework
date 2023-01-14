<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");

function active($needle){
    return current_route('name') == $needle ? 'btn-dark' : 'btn-outline-dark';
}

?>

<div class="row vh-75 ">
<div class="col-md-3 vh-75">
    <div class="d-flex flex-column mx-5 alert border border-dark border-2 h-100">
        <div class="text-center m-2">
            <a class="btn  w-75 <?=active('champs.admin.databasesHome')?>" href="<?=$router->route("champs.admin.databasesHome")?>">Home</a>
        </div>
        <div class="text-center m-2">
            <a class="btn  w-75 <?=active('champs.admin.databasesConnectionList')?>" href="<?=$router->route("champs.admin.databasesConnectionList")?>">Connections</a>
        </div>
        <div class="text-center m-2">
            <a class="btn  w-75 <?=active('champs.admin.databasesAliasesList')?>" href="<?=$router->route("champs.admin.databasesAliasesList")?>">Aliases</a>
        </div>
    </div>
</div>



<div class="col">
    <?= $v->section("content"); ?>
</div>

</div>








