<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2>Recover your password</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
                    <label for="email" class="form-label">Enter your email</label>
                </div>
                <div class="d-grid gap-2 col-12 mx-auto">
                    <input type="submit" class="btn btn-primary" value="Send me a link to recover my password">
                </div>
            </form>
        </div>
        <div class="col-md-6 order-md-1">
            <div class="col-12">
                <img src="<?= theme("/assets/images/forgot_password.svg", CHAMPS_VIEW_WEB) ?>"
                     alt="Hello New Customer" class="img-fluid">
            </div>
            <div class="col-12" id="link-container">
                <a href="<?= $router->route("login.form") ?>">Back to login page</a>
            </div>
        </div>
    </div>
</div>