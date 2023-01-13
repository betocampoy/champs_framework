<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="name"
                               value=""
                               name="name" placeholder="Enter the permission's name">
                        <label for="name" class="form-label">Enter the permission's name</label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button"
                        <?= csrf_data_attr() ?>
                            data-send_inputs="true"
                            data-post="<?= $router->route("champs.admin.permissionsSave") ?>"
                            class="btn btn-primary sendForm">Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
