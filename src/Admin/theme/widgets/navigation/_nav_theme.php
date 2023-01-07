<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigations */

$active = function (string $route) {
    return current_route('name') === $route ? "active" : '';
};

$v->layout("_theme");
?>

<div class="bg-dark text-white">
    <h3 class="text-white bg-dark p-2 text-center">Dynamically create and manage Navbars for you application</h3>
</div>

<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs">
            <li class="nav-item">
                <a class="nav-link <?= $active('champs.admin.navigationHome') ?>"
                   href="<?= $router->route("champs.admin.navigationHome") ?>">Home</a>
            </li>
            <?php if ($navigations->entityExists()): ?>
                <li class="nav-item">
                    <a class="nav-link <?= $active('champs.admin.navigationList') ?> "
                       href="<?= $router->route("champs.admin.navigationList") ?>">List Nav Items</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">List Nav Items</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="card-body">
        <?= $v->section("content"); ?>
    </div>
</div>






