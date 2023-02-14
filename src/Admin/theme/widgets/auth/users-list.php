<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Auth\User $users */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/auth/_auth_theme");
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h2><?=$title ?? "List"?></h2>
            <button class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?>
                    id="btn-create"
                    data-disable_button="false"
                    data-route="<?=$router->route("champs.admin.usersCreate")?>">Create New</button>
        </div>
    </div>
    <div class="card-body">
        <form class="row row-cols-lg-auto g-3 align-items-center"
              action="<?=$router->route("champs.admin.usersList")?>" method="get">

            <input type="hidden" name="search_form_opr_name" value="CONTAIN"/>
            <input type="hidden" name="search_form_opr_email" value="CONTAIN"/>

            <div class="col-6 form-floating mb-3">
                <input class="form-control" placeholder="Search a user"
                       name="search_form_field_name" id="search_form_field_name">
                <label for="search_form_field_name" class="form-label">Search a user</label>
            </div>
            <div class="col-6 form-floating mb-3">
                <input class="form-control" placeholder="Search a e-mail"
                       name="search_form_field_email" id="search_form_field_email">
                <label for="search_form_field_email" class="form-label">Search a e-mail</label>
            </div>
            <button class='col-4 btn btn-outline-success'
                    type='submit'>Search</button>
        </form>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">E-mail</th>
                <th scope="col">Access Level</th>
                <th scope="col">Status</th>
                <th scope="col">Created At</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($users->count() == 0): ?>

            <?php else: ?>

                <?php foreach ($users->fetch(true) as $user): ?>
                    <tr>
                        <th scope="row"><?=$user->id?></th>
                        <td><a class="btn btn-link champs_send_post_on_click" <?=csrf_data_attr()?> id="link-usersEdit"
                               data-disable_button="false"
                               data-route="<?=$router->route("champs.admin.usersEdit", ["id" => $user->id])?>" href="#"><?=$user->name?></a></td>
                        <td><?=$user->last_name?></td>
                        <td><?=$user->email?></td>
                        <td><?=$user->accessLevel()->name?></td>
                        <td><?=$user->status?></td>
                        <td><?=$user->created_at?></td>
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

