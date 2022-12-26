<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2>Recuperar sua senha</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email">
                    <label for="email" class="form-label">Digite seu email</label>
                </div>
                <input type="submit" class="btn btn-primary" value="Entrar">
            </form>
        </div>
        <div class="col-md-6 order-md-1">
            <div class="col-12">
                <img src="<?=theme("/assets/images/forgot_password.svg", CHAMPS_VIEW_WEB)?>" alt="Hello New Customer" class="img-fluid">
            </div>
            <div class="col-12" id="link-container">
                <a href="<?=$router->route("login.form")?>">Voltar e entrar</a>
            </div>
        </div>
    </div>
</div>

<?php if (false):?>

<article class="auth">
    <div class="auth_content container content">
        <header class="auth_header">
            <h1>Recuperar senha</h1>
            <p>Informe seu e-mail para receber um link de recuperação.</p>
        </header>

        <form class="auth_form" data-reset="true" action="<?= url("/recuperar"); ?>" method="post"
              enctype="multipart/form-data">

            <div class="ajax_response"><?= flash(); ?></div>
            <?= csrf_input(); ?>

            <label>
                <div>
                    <span class="icon-envelope">Email:</span>
                    <span><a title="Voltar e entrar!" href="<?= url("/entrar"); ?>">Voltar e entrar!</a></span>
                </div>
                <input type="email" name="email" placeholder="Informe seu e-mail:" required/>
            </label>

            <button class="auth_form_btn transition gradient gradient-green gradient-hover">Recuperar</button>
        </form>
    </div>
</article>

<?php endif ?>