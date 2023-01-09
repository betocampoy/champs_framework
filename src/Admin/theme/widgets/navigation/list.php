<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigations */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/navigation/_nav_theme");
?>

<h2>List of Navigation Itens</h2>

<table class="table">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Theme</th>
        <th scope="col">Display Name</th>
        <th scope="col">Route</th>
        <th scope="col">Visible</th>
        <th scope="col">Parent</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($navigations->count() == 0): ?>

    <?php else: ?>

        <?php foreach ($navigations->fetch(true) as $nav): ?>
            <tr>
                <th scope="row"><a class="btn btn-link sendForm" <?=csrf_data_attr()?>
                                   data-post="<?=$router->route("champs.admin.navigationEdit", ["id" => $nav->id])?>" href="#"><?=$nav->id?></a></th>
                <td><?=$nav->theme_name?></td>
                <td><?=$nav->display_name?></td>
                <td><?=$nav->route?></td>
                <td><?=$nav->visible ? 'Yes' : 'No'?></td>
                <td><?= (!empty($nav->parent_id) && $nav->parent_id > 0) ? $nav->parent() : 'root item'?></td>
            </tr>

        <?php endforeach; ?>
    <?php endif; ?>

    </tbody>

</table>

<div class="container">
    <?=$pager->render()?>
</div>