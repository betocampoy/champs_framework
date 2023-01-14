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
            <button class="btn btn-primary sendForm" <?=csrf_data_attr()?> data-disable_button="false"
                    data-post="<?=$router->route("champs.admin.usersCreate")?>">Create New</button>
        </div>
    </div>
    <div class="card-body">
        <form class="row row-cols-lg-auto g-3 align-items-center" >
            <div class="col-6 form-floating mb-3">
                <input class="form-control" placeholder="Search a permission"
                       name="s" id="s">
                <label for="s" class="form-label">Search a user</label>
            </div>
            <button class='col-4 btn btn-outline-success sendForm'
                <?=csrf_data_attr()?>
                    data-post="<?=$router->route("champs.admin.usersSearch")?>"
                    data-send_inputs="true"
                    data-disable_button="false"
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
                        <td><a class="btn btn-link sendForm" <?=csrf_data_attr()?>
                               data-disable_button="false"
                               data-post="<?=$router->route("champs.admin.usersEdit", ["id" => $user->id])?>" href="#"><?=$user->name?></a></td>
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

