<?php
/** @var string $seo */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2>Login to access the system</h2>
            <form method="post">
                <?= csrf_input() ?>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter you e-mail">
                    <label for="email" class="form-label">Enter you e-mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter your password">
                    <label for="password" class="form-label">Enter your password</label>
                </div>
                <div class="d-grid gap-2 col-12 col-md-6 mx-auto">
                    <input type="submit" class="btn btn-primary champs_send_post_on_click"  id="btn-login"
                           data-route="<?= $router->route("login") ?>"
                           data-with_inputs="true" value="Login">
                    <?=facebookButtonLogin()?>
                </div>
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