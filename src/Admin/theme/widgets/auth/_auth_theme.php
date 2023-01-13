<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");
?>

<div class="row vh-75 ">
<div class="col-md-3 vh-75">
    <div class="d-flex flex-column mx-5 alert border border-dark border-2 h-100">
        <div class="text-center m-2">Home</div>
        <div class="text-center m-2">Users</div>
        <div class="text-center m-2">Roles</div>
        <div class="text-center m-2">
            <a href="<?=$router->route("champs.admin.permissionsHome")?>">Permissions</a>
        </div>
    </div>
</div>



<div class="col">
    <?= $v->section("content"); ?>
</div>

</div>








