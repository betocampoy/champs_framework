<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2 class="text-center">Well Done!!</h2>
            <p class="text-center h3 mb-4"><strong>CHAMPS</strong>framework is running</p>
            <div class="d-grid gap-2 col-8 mx-auto">
                <a class="btn btn-secondary"
                   href="<?= CHAMPS_URL_DOCUMENTATION; ?>" title="Documentation">Consult online documentation!</a>
            </div>
        </div>
        <div class="col-md-6 order-md-1">
            <div class="col-12">
                <img src="<?=theme("/assets/images/astronaut.svg")?>" alt="Hello New Customer"
                     class="img-fluid">
            </div>
            <div class="col-12" id="link-container">
                <a href="<?= $router->route("login.form") ?>">Login Example</a>
            </div>
        </div>
    </div>
</div>

