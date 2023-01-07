<?php
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigations */
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
                <th scope="row"><?=$nav->id?></th>
                <td><?=$nav->theme?></td>
                <td><?=$nav->display_name?></td>
                <td><?=$nav->route?></td>
                <td><?=$nav->visible?></td>
                <td><?= (!empty($nav->parent_id) && $nav->parent_id > 0) ? $nav->parent() : 'root item'?></td>
            </tr>

        <?php endforeach; ?>
    <?php endif; ?>

    </tbody>

</table>

<div class="container">
    <?=$pager->render()?>
</div>