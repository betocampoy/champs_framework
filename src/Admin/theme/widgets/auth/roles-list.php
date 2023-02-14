<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Auth\Role $roles */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/auth/_auth_theme");
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h2><?=$title ?? "List"?></h2>
            <button class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-rolesCreate"
                    data-disable_button="false"
                    data-route="<?=$router->route("champs.admin.rolesCreate")?>">Create New</button>
        </div>
    </div>
    <div class="card-body">
        <form class="row row-cols-lg-auto g-3 align-items-center" >
            <div class="col-6 form-floating mb-3">
                <input class="form-control" placeholder="Search a permission"
                       name="s" id="s">
                <label for="s" class="form-label">Search a role</label>
            </div>
            <button class="col-4 btn btn-outline-success champs_send_post_on_click" <?=csrf_data_attr()?> id="link-rolesSearch"
                    data-route="<?=$router->route("champs.admin.rolesSearch")?>"
                    data-with_inputs="true"
                    data-disable_button="false"
                    type='submit'>Search</button>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Access Level</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($roles->count() == 0): ?>

            <?php else: ?>

                <?php foreach ($roles->fetch(true) as $role): ?>
                    <tr>
                        <th scope="row"><?=$role->id?></th>
                        <td><a class="btn btn-link champs_send_post_on_click" <?=csrf_data_attr()?> id="link-rolesEdit"
                               data-disable_button="false"
                               data-route="<?=$router->route("champs.admin.rolesEdit", ["id" => $role->id])?>" href="#"><?=$role->name?></a></td>
                        <td><?=str_title($role->accessLevel()->name)?></td>
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

