<?php
/** @var string $alias */
/** @var string $environment */
/** @var string $connection */
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
                            <option value="DEV" <?= option_is_selected('DEV', $environment) ?>>Development</option>
                            <option value="UAT" <?= option_is_selected('UAT', $environment) ?>>Test</option>
                            <option value="PRD" <?= option_is_selected('PRD', $environment) ?>>Production</option>
                        </select>
                        <label for="environment" class="form-label">Select the environment?</label>
                        <div id="environment_help" class="form-text">Select the environmet of this alias</div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="alias"
                               value="<?=$alias?>"
                               name="alias" placeholder="Alias name">
                        <label for="alias" class="form-label">Alias name</label>
                        <div id="aliase_help" class="form-text">Defin a name for the alias.</div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Select the connection" name="connection"
                                id="connection">
                            <option selected disabled>Select one connection bellow</option>
                            <?php foreach ($connections as $connName => $params): ?>
                                <option
                                    <?= option_is_selected($connName, $connection) ?>
                                        value="<?= $connName ?>"><?= $connName ?> - <?= $params['dbhost'] ?>
                                    - <?= $params['dbname'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="environment" class="form-label">Select the connection</label>
                        <div id="environment_help" class="form-text">Select one connection to link</div>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button"
                        <?= csrf_data_attr() ?>
                            data-confirm="Do you really want to delete the alias [<?=$alias?>] registered in <?=$environment?>?"
                            data-post="<?= $router->route("champs.admin.databasesAliasesDelete", ['id' => "{$environment}-{$alias}"]) ?>"
                            class="btn btn-link text-danger sendForm">Delete
                    </button>
                    <button type="button"
                        <?= csrf_data_attr() ?>
                            data-send_inputs="true"
                            data-post="<?= $router->route("champs.admin.databasesAliasesUpdate", ['id' => "{$environment}-{$alias}"]) ?>"
                            class="btn btn-primary sendForm">Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
