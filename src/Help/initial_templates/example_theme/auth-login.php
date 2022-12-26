<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2>Login to access the system</h2>
            <form method="post">
                <?= csrf_input() ?>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Type you e-mail">
                    <label for="email" class="form-label">Type you e-mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Type your password">
                    <label for="password" class="form-label">Type your password</label>
                </div>
                <input type="submit" class="btn btn-primary sendForm" data-send_inputs="true" value="Entrar">
                <?=facebookButtonLogin()?>
            </form>
        </div>
        <div class="col-md-6 order-md-1">
            <div class="col-12">
                <img src="<?= theme("/assets/images/sign_in.svg", CHAMPS_VIEW_WEB) ?>" alt="Hello New Customer"
                     class="img-fluid">
            </div>
            <div class="col-12" id="link-container">
                <a href="<?= $router->route("register.form") ?>">Sign in</a>
                <a class="m-5" href="<?= $router->route("forget.form") ?>">Recover password</a>
            </div>
        </div>
    </div>
</div>