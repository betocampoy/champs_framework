<?php
/** @var array $aliases */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/databases/_databases_theme");
?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h2><?= $title ?? "list" ?></h2>
            <button class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbAliasCreate"
                    data-disable_button="false"
                    data-route="<?= $router->route("champs.admin.databasesAliasesCreate") ?>">Create New
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Environment</th>
                <th scope="col">Alias</th>
                <th scope="col">Connection</th>
            </tr>
            </thead>
            <tbody>
            <?php if (count($aliases) == 0): ?>

            <?php else: ?>

                <?php foreach ($aliases as $env => $connections): ?>
                    <?php foreach ($connections as $name => $connection): ?>
                        <tr>
                            <th scope="row"><?= $env ?></th>
                            <td><a class="btn btn-link champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbAliasEdit"
                                   data-disable_button="false"
                                   data-route="<?= $router->route("champs.admin.databasesAliasesEdit", ["id" => "{$env}-{$name}"]) ?>"
                                   href="#"><?= $name ?></a></td>
                            <td><a class="btn btn-link champs_send_post_on_click" <?=csrf_data_attr()?> id="link-dbConnEdit"
                                   data-disable_button="false"
                                   data-post="<?=$router->route("champs.admin.databasesConnectionEdit", ["id" => $connection])?>"
                                   href="#"><?=$connection?></a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

