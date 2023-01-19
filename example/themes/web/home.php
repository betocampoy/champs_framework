<?php
/** @var string $seo */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2 class="text-center">Welcome!!</h2>
            <p class="text-center h3 mb-4">This is a example theme, replace it for your own theme</p>
            <div class="d-grid gap-2 col-8 mx-auto">
                <a class="btn btn-secondary"
                   href="<?= CHAMPS_URL_DOCUMENTATION; ?>" target="_blank" title="Documentation">Documentation!</a>
                <a class="btn btn-secondary"
                   href="<?= url("/champsframework"); ?>" target="_blank" title="Documentation">Admin Panel!</a>
            </div>
        </div>
        <div class="col-md-6 order-md-1">
            <div class="col-12">
                <img src="<?=theme("/assets/images/programming.svg")?>" alt="Hello New Customer"
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>

