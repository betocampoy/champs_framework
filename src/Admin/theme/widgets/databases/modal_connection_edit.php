<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var string $connection_name */
/** @var array $connection_params */

?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Connection <?=$connection_name?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name"
                               value="<?=$connection_name?>"
                               name="name" placeholder="Connection Name">
                        <label for="name" class="form-label">Connection Name</label>
                        <div id="name_help" class="form-text">
                            Enter an unique (slug) name for the connection.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbname"
                               value="<?=$connection_params['dbname']?>"
                               name="dbname" placeholder="Database Name">
                        <label for="dbname" class="form-label">Database Name</label>
                        <div id="dbname_help" class="form-text">
                            Enter database name.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbhost"
                               value="<?=$connection_params['dbhost']?>"
                               name="dbhost" placeholder="Server Hostname">
                        <label for="dbhost" class="form-label">Server Hostname</label>
                        <div id="dbhost_help" class="form-text">
                            Enter the server's hostname.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbport"
                               value="<?=$connection_params['dbport']?>"
                               name="dbport" placeholder="TCP Access Port">
                        <label for="dbport" class="form-label">TCP Access Port</label>
                        <div id="dbport_help" class="form-text">Enter the database TCP port (default 3306)</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbuser"
                               value="<?=$connection_params['dbuser']?>"
                               name="dbuser" placeholder="Username">
                        <label for="dbuser" class="form-label">Username</label>
                        <div id="dbport_help" class="form-text">Enter the database username</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="dbpass"
                               value="<?=$connection_params['dbpass']?>"
                               name="dbpass" placeholder="User Password">
                        <label for="dbpass" class="form-label">User Password</label>
                        <div id="dbport_help" class="form-text">Enter the user password</div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-link text-danger champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbConnDel"
                            data-confirm="Confirm delete of <?=$connection_name?>?"
                            data-route="<?= $router->route("champs.admin.databasesConnectionDelete", ['id' => $connection_name]) ?>"
                            >Delete
                    </button>
                    <button type="button" class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbConnUpdt"
                            data-with_inputs="true"
                            data-route="<?= $router->route("champs.admin.databasesConnectionUpdate", ["id" => $connection_name]) ?>"
                            >Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
