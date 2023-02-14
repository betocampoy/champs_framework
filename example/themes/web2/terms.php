<?php
/** @var string $seo */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-1">
            <h2 class="text-center">Terms!!</h2>
            <p class="text-center h3 mb-4">By registering you are automatically agreeing to our terms.</p>
            <div class="col-12" id="link-container">
                <a href="#">Click here</a> to download a printble version of owr agreement terms in pdf
            </div>
        </div>
        <div class="col-md-6 order-md-2">
            <div class="col-12">
                <img src="<?=theme("/assets/images/agreement.svg")?>" alt="Hello New Customer"
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>

