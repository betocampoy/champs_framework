<?php
/** @var array $connections */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/databases/_databases_theme");
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h2><?=$title ?? "list"?></h2>
            <button class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbConnCreate"
                    data-disable_button="false"
                    data-route="<?=$router->route("champs.admin.databasesConnectionCreate")?>">Create New</button>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Connection Name</th>
                <th scope="col">Hostname</th>
                <th scope="col">Port</th>
                <th scope="col">Database</th>
                <th scope="col">User</th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($connections) == 0): ?>

            <?php else: ?>

                <?php foreach ($connections as $connName => $connection): ?>
                    <tr>
                        <th scope="row"><a class="btn btn-link champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbConnEdit"
                                           data-disable_button="false"
                                           data-route="<?=$router->route("champs.admin.databasesConnectionEdit", ["id" => $connName])?>"
                                           href="#"><?=$connName?></a></th>
                        <td><?=$connection['dbhost'] ?? 'localhost'?></td>
                        <td><?=$connection['dbport'] ?? 3306?></td>
                        <td><?=$connection['dbname']?></td>
                        <td><?=$connection['dbuser']?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

