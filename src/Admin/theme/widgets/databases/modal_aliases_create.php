<?php
/** @var array $aliases */
/** @var array $connections */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create a New Alias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Select the environment" name="environment"
                                id="environment">
                            <option selected disabled>Select the environment?</option>
                            <option value="DEV">Development</option>
                            <option value="UAT">Test</option>
                            <option value="PRD">Production</option>
                        </select>
                        <label for="environment" class="form-label">Select the environment?</label>
                        <div id="environment_help" class="form-text">Select the environmet of this alias</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alias"
                               value="main"
                               name="alias" placeholder="Alias name">
                        <label for="alias" class="form-label">Alias name</label>
                        <div id="aliase_help" class="form-text">Defin a name for the alias.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Select the connection" name="connection"
                                id="connection">
                            <option selected disabled>Select one connection bellow</option>
                            <?php foreach ($connections as $connName => $params): ?>
                                <option value="<?= $connName ?>"><?= $connName ?> - <?= $params['dbhost'] ?>
                                    - <?= $params['dbname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="environment" class="form-label">Select the connection</label>
                        <div id="environment_help" class="form-text">Select one connection to link</div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbAliasSave"
                            data-with_inputs="true"
                            data-route="<?= $router->route("champs.admin.databasesAliasesSave") ?>"
                            >Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
