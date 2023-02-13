<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme")
?>

    <div class="bg-dark text-white">
        <h3 class="text-white bg-dark p-2 text-center">You must login before continue</h3>
    </div>

<?php if (CHAMPS_CONFIG_MASTER_ADMIN_EMAIL): ?>
    <div class="row justify-content-center m-5">
        <div class="col-md-6">
            <div class="card mb-3">
                <div class="card-header">
                    <h6><strong>Inform master admin credentials</strong></h6>
                </div>

                <div class="card-body">
                    <form action="">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="master_admin_email"
                                   value=""
                                   name="master_admin_email"
                                   placeholder="Enter the e-mail registered during initial setup">
                            <label for="master_admin_email" class="form-label">Enter the e-mail registered
                                during initial setup</label>
                        </div>

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="master_admin_password"
                                   value=""
                                   name="master_admin_password"
                                   placeholder="Enter the password registered during initial setup">
                            <label for="master_admin_password" class="form-label">Enter the password registered
                                during initial setup</label>
                        </div>
                        <button class="btn btn-primary champs_send_post_on_click" id="btn-login"
                                <?=csrf_data_attr()?>
                                data-send_inputs="true"
                                data-route="<?= $router->route("champs.admin.login") ?>">Submit</button>
                    </form>
                </div>

            </div>
        </div>

    </div>

<?php else: ?>

    <div class="alert alert-danger text-center">
        <strong><p>ATENTION: There is something wrong in framework configuration file. The master administrator
                credential were deleted.</p></strong>
        <p>Configure the authentication
            based in database and you will achive to login in Administrative Panel again.</p>
    </div>

<?php endif; ?>