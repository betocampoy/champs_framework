<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Auth\Permission $permissions */

$active = function (array $routes) {
    return in_array(current_route('name'), $routes ) ? "active" : '';
};

$v->layout("_theme");
?>

<div class="bg-dark text-white">
    <h3 class="text-white bg-dark p-2 text-center">Manage the PERMISSIONS here</h3>
</div>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link <?= $active(['champs.admin.navigationHome']) ?>"
                   href="<?= $router->route("champs.admin.navigationHome") ?>">Home</a>
            </li>
            <?php if (!$permissions->entityExists()): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active(['champs.admin.navigationList', 'champs.admin.navigationSearch']) ?> "
                       href="<?= $router->route("champs.admin.navigationList") ?>">List Permissions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link sendForm" <?=csrf_data_attr()?> tabindex="-1" aria-disabled="true"
                       data-disable_button="false"
                       data-post="<?=$router->route("champs.admin.navigationCreate")?>" href="#">Create Permission</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">List Permissions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Create Permission</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card-body">
        <?= $v->section("content"); ?>
    </div>
</div>






