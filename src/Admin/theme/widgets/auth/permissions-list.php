<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Auth\Permission $permissions */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/auth/_auth_theme");
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h2>List of Permissions</h2>
            <button class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-permission"
                    data-disable_button="false"
                    data-route="<?=$router->route("champs.admin.permissionsCreate")?>">Create New</button>
        </div>
    </div>
    <div class="card-body">
        <form class="row row-cols-lg-auto g-3 align-items-center" >
            <div class="col-6 form-floating mb-3">
                <input class="form-control" placeholder="Search a permission"
                       name="s" id="s">
                <label for="s" class="form-label">Search a permission</label>
            </div>
            <button class="col-4 btn btn-outline-success champs_send_post_on_click" <?=csrf_data_attr()?> id="link-permisionSearch"
                    data-route="<?=$router->route("champs.admin.permissionsSearch")?>"
                    data-with_inputs="true"
                    data-disable_button="false"
                    type='submit'>Search</button>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Permission</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($permissions->count() == 0): ?>

            <?php else: ?>

                <?php foreach ($permissions->fetch(true) as $permission): ?>
                    <tr>
                        <th scope="row"><?=$permission->id?></th>
                        <td><a class="btn btn-link schamps_send_post_on_click" <?=csrf_data_attr()?> id="link-permissionEdit"
                               data-disable_button="false"
                               data-route="<?=$router->route("champs.admin.permissionsEdit", ["id" => $permission->id])?>" href="#"><?=$permission->name?></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>

        </table>

        <div class="container">
            <?=$pager->render()?>
        </div>
    </div>
</div>

