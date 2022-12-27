<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row gx-5">
        <div class="col-md-6">
            <h2>Realize o seu cadastro</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Digite seu nome">
                    <label for="name" class="form-label">Digite seu nome</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Digite seu sobrenome">
                    <label for="last_name" class="form-label">Digite seu sobrenome</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Digite seu email">
                    <label for="email" class="form-label">Digite seu email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha">
                    <label for="password" class="form-label">Digite sua senha</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirme sua senha">
                    <label for="confirm_password" class="form-label">Confirme sua senha</label>
                </div>
                <div class="mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" value="" id="agree-term">
                        <label class="form-check-label" for="agree-term">
                            Você aceita os <a href="#">termos de serviço</a>?
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="newsletter" checked>
                        <label class="form-check-label" for="newsletter">
                            Deseja receber as nossas Newsletters?
                        </label>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary sendForm"
                       data-post="<?=$router->route("register")?>"
                       data-send_inputs="true" <?=csrf_data_attr()?> value="Cadastrar">
            </form>
        </div>
        <div class="col-md-6">
            <div class="row align-items-center">
                <div class="col-12">
                    <img src="<?=theme("/assets/images/hello.svg", CHAMPS_VIEW_WEB)?>" alt="Hello New Customer" class="img-fluid">
                </div>
                <div class="col-12" id="link-container">
                    <a href="<?=$router->route("login.form")?>">Eu já tenho uma conta</a>
                </div>
            </div>
        </div>
    </div>
</div>