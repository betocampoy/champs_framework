<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $root_items */
/** @var array $theme_names */
?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Navigation Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="form-floating mb-3">
                        <input class="form-control filter" <?=csrf_data_attr()?>
                               data-disable_button="false"
                               data-post="<?=$router->route("champs.admin.navigationFilterRoot")?>"
                               data-index="1"
                               list="datalistOptions" placeholder="Enter or select the theme name"
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
                            <option value="" selected disabled>
                                Select the theme before continue
                            </option>

                        </select>
                        <label for="section_init" class="form-label">Select the position in menu</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="display_name"
                               value=""
                               name="display_name" placeholder="Enter the page's display name">
                        <label for="display_name" class="form-label">Enter the page's display name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="route"
                               value=""
                               name="route"
                               placeholder="Enter the route">
                        <label for="route" class="form-label">Enter the route</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Show item in navbar" name="visible">
                            <option selected disabled>Show this item in navbar?</option>
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                        <label for="visible" class="form-label">Show item in navbar</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="This item is public" name="public">
                            <option selected disabled>This item is public?</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                        <label for="public" class="form-label">This item is public?</label>
                    </div>
                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="This item starts a new section" name="section_init">
                            <option selected disabled>This item starts a new section?</option>
                            <option value="1">Yes</option>
                            <option value="0" selected>No</option>
                        </select>
                        <label for="section_init" class="form-label">This item starts a new section?</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="permissions"
                               value=""
                               name="permissions"
                               placeholder="Enter permissions needed to access">
                        <label for="permissions" class="form-label">Enter permissions needed to access</label>
                        <div id="permissions_help" class="form-text">
                            Use ';' to delimite multiple permissions. Example: users view;roles view
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="external_functions"
                               value=""
                               name="external_functions"
                               placeholder="Enter if this item has some external attribute">
                        <label for="external_functions" class="form-label">Enter if this item has some external
                            attribute</label>
                        <div id="external_functions_help" class="form-text">
                            Example: target='_blank'
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary champs_send_post_on_click" <?=csrf_data_attr()?> id="link-navSave"
                            data-with_inputs="true"
                            data-route="<?= $router->route("champs.admin.navigationSave") ?>"
                            >Save changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
