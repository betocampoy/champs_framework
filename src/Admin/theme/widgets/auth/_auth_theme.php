<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");
?>

<div class="row vh-75 ">
<div class="col-md-3 vh-75">
    <div class="d-flex flex-column mx-5 alert border border-dark border-2 h-100">
        <div class="text-center m-2">
            <a class="btn btn-outline-dark w-75" href="<?=$router->route("champs.admin.permissionsHome")?>">Home</a>
        </div>
        <div class="text-center m-2">
            <a class="btn btn-outline-dark w-75" href="<?=$router->route("champs.admin.permissionsHome")?>">Users</a>
        </div>
        <div class="text-center m-2">
            <a class="btn btn-outline-dark w-75" href="<?=$router->route("champs.admin.permissionsHome")?>">Roles</a>
        </div>
        <div class="text-center m-2">
            <a class="btn btn-outline-dark w-75" href="<?=$router->route("champs.admin.permissionsHome")?>">Permissions</a>
        </div>
    </div>
</div>



<div class="col">
    <?= $v->section("content"); ?>
</div>

</div>








