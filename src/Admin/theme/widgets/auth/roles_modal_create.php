<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>

<form action="">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?= $title ?? "Create New" ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name"
                       value=""
                       name="name" placeholder="Enter the role's name">
                <label for="name" class="form-label">Enter the role's name</label>
                <div id="environment_help" class="form-text">Enter the role's name. Role is used to group many
                    permissions
                    and simplify give permissions to users!
                </div>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" aria-label="Select access level of this role"
                        name="access_level_id"
                        id="access_level_id">
                    <option selected disabled>Select the access level</option>
                    <?php foreach ($accessLevels->fetch(true) as $accessLevel) : ?>
                        <option value="<?= $accessLevel->id ?>"><?= $accessLevel->name ?></option>
                    <?php endforeach ?>
                </select>
                <label for="access_level_id" class="form-label">Select access level of this role</label>
                <div id="access_level_help" class="form-text">
                    Each role always must be reletad to one access level. Admin, Operator or Clientes.
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button"
                <?= csrf_data_attr() ?>
                    data-send_inputs="true"
                    data-post="<?= $router->route("champs.admin.rolesSave") ?>"
                    class="btn btn-primary sendForm">Save changes
            </button>
        </div>
    </div>
</form>
