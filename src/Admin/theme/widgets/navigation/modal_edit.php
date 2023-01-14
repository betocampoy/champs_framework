<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigation */
/** @var array $theme_names */
$idx = 0;
?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Navigation Item
                        [<?= $navigation->display_name ?>
                        ]</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input class="form-control filter" <?=csrf_data_attr()?>
                               data-disable_button="false"
                               data-post="<?=$router->route("champs.admin.navigationFilterRoot")?>"
                               data-index="1"
                               list="datalistOptions" placeholder="Enter or select the theme name" value="<?=$navigation->theme_name?>"
                               name="theme_name" id="theme_name">
                        <datalist id="datalistOptions">
                            <?php foreach ($theme_names as $theme): ?>
                                <option value="<?=$theme?>"><?=$theme?></option>
                            <?php endforeach; ?>
                        </datalist>
                        <label for="theme_name" class="form-label">Enter or select the theme name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" data-index="2" aria-label="Parent" name="parent_id">
                            <option value="" <?= $navigation->parent_id == null || $navigation->parent_id == 0 ? 'select' : '' ?>>
                                Root Item
                            </option>
                            <?php foreach ($root_items->fetch(true) as $item): ?>
                                <option value="<?= $item->id ?>" <?= option_is_selected($item->id, $navigation->parent_id) ?>><?= $item->display_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="section_init" class="form-label">Select the position in menu</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="display_name"
                               value="<?= $navigation->display_name ?>"
                               name="display_name" placeholder="Enter the page's display name">
                        <label for="display_name" class="form-label">Enter the page's display name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="route"
                               value="<?= $navigation->route ?>"
                               name="route"
                               placeholder="Enter the route">
                        <label for="route" class="form-label">Enter the route</label>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Show item in navbar" name="visible">
                            <option selected disabled>Show this item in navbar?</option>
                            <option value="1" <?= option_is_selected(1, $navigation->visible) ?>>Yes</option>
                            <option value="0" <?= option_is_selected(0, $navigation->visible) ?>>No</option>
                        </select>
                        <label for="visible" class="form-label">Show item in navbar</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="This item is public" name="public">
                            <option selected disabled>This item is public?</option>
                            <option value="1" <?= option_is_selected(1, $navigation->public) ?>>Yes</option>
                            <option value="0" <?= option_is_selected(0, $navigation->public) ?>>No</option>
                        </select>
                        <label for="public" class="form-label">This item is public?</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="This item starts a new section" name="section_init">
                            <option selected disabled>This item starts a new section?</option>
                            <option value="1" <?= option_is_selected(1, $navigation->section_init) ?>>Yes</option>
                            <option value="0" <?= option_is_selected(0, $navigation->section_init) ?>>No</option>
                        </select>
                        <label for="section_init" class="form-label">This item starts a new section?</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="permissions"
                               value="<?= $navigation->permissions ?>"
                               name="permissions"
                               placeholder="Enter permissions needed to access">
                        <label for="permissions" class="form-label">Enter permissions needed to access</label>
                        <div id="permissions_help" class="form-text">
                            Use ';' to delimite multiple permissions. Example: users view;roles view
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="external_functions"
                               value="<?= $navigation->external_functions ?>"
                               name="external_functions"
                               placeholder="Enter if this item has some external attribute">
                        <label for="external_functions" class="form-label">Enter if this item has some external
                            attribute</label>
                        <div id="external_functions_help" class="form-text">
                            Example: target='_blank'
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Inform the order sequence" name="sequence">
                            <?php foreach ($sequences->fetch(true) as $sequence): $idx = $sequence->sequence; ?>
                                <option value="<?= $sequence->sequence ?>" <?= option_is_selected($sequence->sequence, $navigation->sequence) ?>><?= $sequence->sequence ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label for="section_init" class="form-label">Inform the order sequence?</label>
                    </div>

                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button"
                        <?=csrf_data_attr()?>
                            data-id="<?=$navigation->id?>"
                            data-confirm="Are you sure that you want to delete the item [<?=$navigation->display_name ?? $navigation->id?>]? Their children will be deleted too"
                            data-post="<?= $router->route("champs.admin.navigationDelete", ["id" => $navigation->id]) ?>"
                            class="btn btn-link text-danger sendForm">Excluir
                    </button>
                    <button type="button"
                            <?=csrf_data_attr()?>
                            data-send_inputs="true"
                            data-post="<?= $router->route("champs.admin.navigationUpdate", ["id" => $navigation->id]) ?>"
                            class="btn btn-primary sendForm">Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
