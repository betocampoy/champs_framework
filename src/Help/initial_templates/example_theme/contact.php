<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row gx-5">
        <div class="col-md-6">
            <h2>Contact Form</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name">
                    <label for="name" class="form-label">Enter name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your e-mail">
                    <label for="email" class="form-label">Enter your e-mail</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter the subject">
                    <label for="subject" class="form-label">Enter the subject</label>
                </div>

                <div class="form-floating mb-3">
                    <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                    <label for="floatingTextarea">Comments</label>
                </div>
                <div class="d-grid gap-2 col-12 col-md-6">
                    <input type="submit" class="btn btn-primary sendForm"
                           data-post="<?= $router->route("register") ?>"
                           data-send_inputs="true" <?= csrf_data_attr() ?> value="Send">
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="row align-items-center">
                <div class="col-12">
                    <img src="<?= theme("/assets/images/personal_opinions.svg", CHAMPS_VIEW_WEB) ?>" alt="Hello New Customer"
                         class="img-fluid">
                </div>
                <div class="col-12" id="link-container">
                    <a href="<?= $router->route("login.form") ?>">I already have an account</a>
                </div>
            </div>
        </div>
    </div>
</div>