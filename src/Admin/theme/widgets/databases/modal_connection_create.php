<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create a New Connection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name"
                               value=""
                               name="name" placeholder="Connection Name">
                        <label for="name" class="form-label">Connection Name</label>
                        <div id="name_help" class="form-text">
                            Enter an unique (slug) name for the connection.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbname"
                               value=""
                               name="dbname" placeholder="Database Name">
                        <label for="dbname" class="form-label">Database Name</label>
                        <div id="dbname_help" class="form-text">
                            Enter database name.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbhost"
                               value="localhost"
                               name="dbhost" placeholder="Server Hostname">
                        <label for="dbhost" class="form-label">Server Hostname</label>
                        <div id="dbhost_help" class="form-text">
                            Enter the server's hostname.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbport"
                               value="3306"
                               name="dbport" placeholder="TCP Access Port">
                        <label for="dbport" class="form-label">TCP Access Port</label>
                        <div id="dbport_help" class="form-text">Enter the database TCP port (default 3306)</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="dbuser"
                               value=""
                               name="dbuser" placeholder="Username">
                        <label for="dbuser" class="form-label">Username</label>
                        <div id="dbport_help" class="form-text">Enter the database username</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="dbpass"
                               value=""
                               name="dbpass" placeholder="User Password">
                        <label for="dbpass" class="form-label">User Password</label>
                        <div id="dbport_help" class="form-text">Enter the user password</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button"
                        <?= csrf_data_attr() ?>
                            data-send_inputs="true"
                            data-post="<?= $router->route("champs.admin.databasesConnectionSave") ?>"
                            class="btn btn-primary sendForm">Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
