<?php
/** @var string $seo */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row gx-5">
        <div class="col-md-6">
            <h2>Do you registration</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name">
                    <label for="name" class="form-label">Enter your first name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="last_name" name="last_name"
                           placeholder="Enter you last name">
                    <label for="last_name" class="form-label">Enter you last name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your e-mail">
                    <label for="email" class="form-label">Enter your e-mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="Enter your password">
                    <label for="password" class="form-label">Enter your password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                           placeholder="Confirm your password">
                    <label for="confirm_password" class="form-label">Confirm your password</label>
                </div>
                <div class="mb-3">
                    <div class="form-check mb-2">
                        <?//php if(CHAMPS_LINK_OF_AGREE_TERMS):?>
                        <input class="form-check-input" type="checkbox" value="" id="agree-term" name="agree-term">
                        <label class="form-check-label" for="agree-term">
                            Do you agree with our <a href="#">service terms</a>?
                        </label>
                        <?//php endif ?>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="newsletter" name="newsletter" checked>
                        <label class="form-check-label" for="newsletter">
                            Do you wanna subscribe our newsletter?
                        </label>
                    </div>
                </div>
                <div class="d-grid gap-2 col-12 col-md-6">
                    <input type="submit" class="btn btn-primary sendForm"
                           data-post="<?= $router->route("register") ?>"
                           data-send_inputs="true" <?= csrf_data_attr() ?> value="Register">
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="row align-items-center">
                <div class="col-12">
                    <img src="<?= theme("/assets/images/hello.svg", CHAMPS_VIEW_WEB) ?>" alt="Hello New Customer"
                         class="img-fluid">
                </div>
                <div class="col-12" id="link-container">
                    <a href="<?= $router->route("login.form") ?>">I already have an account</a>
                </div>
            </div>
        </div>
    </div>
</div>