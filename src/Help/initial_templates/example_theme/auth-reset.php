<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-1">
            <h2>Altere sua senha</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
                    <label for="password" class="form-label">Digite sua senha</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password_re" name="password_re" placeholder="Confirme sua senha">
                    <label for="password_re" class="form-label">Confirme sua senha</label>
                </div>
                <input type="submit" class="btn btn-primary" value="Entrar">
            </form>
        </div>
        <div class="col-md-6 order-md-2">
            <div class="col-12">
                <img src="<?=theme("/assets/images/secure_login.svg", CHAMPS_VIEW_WEB)?>" alt="Hello New Customer" class="img-fluid">
            </div>
            <div class="col-12" id="link-container">
                <a href="<?=$router->route("login.form")?>">Entrar no sistema</a>
            </div>
        </div>
    </div>
</div>