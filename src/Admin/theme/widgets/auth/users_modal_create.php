<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>

<form action="">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title " id="exampleModalLabel"><?= $title ?? "Create New" ?></h5>
            <button type="button" class="btn-close champs_modal_close" data-champs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your first name">
                <label for="name" class="form-label">Enter the first name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="last_name" name="last_name"
                       placeholder="Enter you last name">
                <label for="last_name" class="form-label">Enter the last name</label>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your e-mail">
                <label for="email" class="form-label">Enter the e-mail</label>
                <div id="access_level_help" class="form-text">
                    An e-mail will be send to user confirm the register and create an password.
                </div>
            </div>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="password" name="password" placeholder="Enter your e-mail">
                <label for="password" class="form-label">Enter the e-mail</label>
                <div id="password_help" class="form-text">
                    An e-mail will be send to user confirm the register and create an password.
                </div>
            </div>

            <div class="form-floating mb-3">
                <select class="form-select" aria-label="Select user's access level of this role"
                        name="access_level_id"
                        id="access_level_id">
                    <option selected disabled>Select the access level</option>
                    <?php foreach ($accessLevels->fetch(true) as $accessLevel) : ?>
                        <option value="<?= $accessLevel->id ?>"><?= $accessLevel->name ?></option>
                    <?php endforeach ?>
                </select>
                <label for="access_level_id" class="form-label">Select user's access level of this role</label>
                <div id="access_level_help" class="form-text">
                    Select the apropriate access level, once created isn't possible to change.
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" id="btn-save"
                <?= csrf_data_attr() ?>
                    data-route="<?= $router->route("champs.admin.usersSave") ?>"
                    class="btn btn-primary champs_send_post_on_click">Save changes
            </button>
        </div>
    </div>
</form>
