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
            <a class="btn w-75 <?=active('champs.admin.authHome')?>"
               href="<?=$router->route("champs.admin.authHome")?>">Home</a>
        </div>
        <div class="text-center m-2">
            <a class="btn w-75 <?=active('champs.admin.usersHome')?>"
               href="<?=$router->route("champs.admin.usersHome")?>">Users</a>
        </div>
        <div class="text-center m-2">
            <a class="btn w-75 <?=active('champs.admin.rolesHome')?>"
               href="<?=$router->route("champs.admin.rolesHome")?>">Roles</a>
        </div>
        <div class="text-center m-2">
            <a class="btn w-75 <?=active('champs.admin.permissionsHome')?>"
               href="<?=$router->route("champs.admin.permissionsHome")?>">Permissions</a>
        </div>
    </div>
</div>



<div class="col">
    <?= $v->section("content"); ?>
</div>

</div>








