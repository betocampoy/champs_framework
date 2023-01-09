<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigations */
/** @var array $theme_names */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("widgets/navigation/_nav_theme");
?>

<h2>List of Navigation Itens</h2>
<form class="row row-cols-lg-auto g-3 align-items-center" >
    <div class="col-6 form-floating mb-3">
        <input class="form-control" list="datalistOptions" placeholder="Enter or select the theme name to filter data"
               name="s" id="s">
        <datalist id="datalistOptions">
            <?php foreach ($theme_names as $theme): ?>
                <option value="<?=$theme?>"><?=$theme?></option>
            <?php endforeach; ?>
        </datalist>
        <label for="theme_name" class="form-label">Enter or select the theme name to filter data</label>
    </div>
    <button class='col-4 btn btn-outline-success sendForm'
            <?=csrf_data_attr()?>
            data-post="<?=$router->route("champs.admin.navigationSearch")?>"
            data-send_inputs="true"
            data-disable_button="false"
            type='submit'>Search</button>
</form>

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
                                   data-disable_button="false"
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