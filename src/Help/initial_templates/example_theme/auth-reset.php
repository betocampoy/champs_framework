<?php $v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-1">
            <h2>Change your password</h2>
            <form>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password">
                    <label for="password" class="form-label">Enter your password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password">
                    <label for="confirm_password" class="form-label">Confirm your password</label>
                </div>
                <div class="d-grid gap-2 col-12 col-md-6 mx-auto">
                    <input type="submit" class="btn btn-primary" value="Save">
                </div>
            </form>
        </div>
        <div class="col-md-6 order-md-2">
            <div class="col-12">
                <img src="<?=theme("/assets/images/secure_login.svg", CHAMPS_VIEW_WEB)?>" alt="Hello New Customer" class="img-fluid">
            </div>
            <div class="col-12" id="link-container">
                <a href="<?=router()->route("login.form")?>">Back to login page</a>
            </div>
        </div>
    </div>
</div>