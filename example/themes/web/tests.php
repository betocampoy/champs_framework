<?php
/** @var string $seo */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
$v->layout("_theme");
?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row gx-5">
        <div class="col-md-6">
            <h2>Contact Form</h2>
            <form>

                <?php if (false):?>
                <select class="form-select champs_send_post_on_update"
                        id="teste"
                        name="teste"
                        data-with_inputs="false"
                        data-controller="<?=\Source\App\Users::class?>"
                        data-route="<?= $router->route("web.default.search") ?>"
                    <?= csrf_data_attr() ?>
                        aria-label="teste">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
<?php endif; ?>
                <select class="form-select champs_send_post_on_update"
                        name="select_test"
                        id="meu_id"
                        data-with_inputs="false"
                        data-route="<?= $router->route("web.testPost") ?>"
                        data-group="updt_select"
                        data-group_index="1"
                        data-child_selector="#select_child1"
                        <?= csrf_data_attr() ?>
                        aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>

                <select class="form-select champs_send_post_on_update" name="select_child1" id="select_child1"
                        data-with_inputs="false" <?= csrf_data_attr() ?>
                        data-route="<?= $router->route("web.testGet") ?>"
                        data-group="updt_select"
                        data-group_index="2"
                        data-child_selector="#select_child2"
                        aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>

                <select class="form-select champs_send_post_on_update" name="select_child2" id="select_child2"
                        data-with_inputs="false" <?= csrf_data_attr() ?>
                        data-route="<?= $router->route("web.testPost") ?>"
                        data-group="updt_select"
                        data-group_index="3"
                        data-child_selector="#name"
                        aria-label="Default select example">
                    <option selected>Open this select menu</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter name"
                           data-group="updt_select"
                           data-group_index="4"
                    >
                    <label for="name" class="form-label">Enter name</label>
                </div>


                <div class="d-grid gap-2 col-12 col-md-6">
                    <input type="submit" class="btn btn-primary sendForm"
                           data-post="<?= $router->route("register") ?>"
                           <?= csrf_data_attr() ?> value="Send">
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="row align-items-center">
                <div class="col-12">
                    <img src="<?= theme("/assets/images/personal_opinions.svg", CHAMPS_VIEW_WEB) ?>" alt="Hello New Customer"
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>


<?php $v->start("scripts")?>
    <script>

        function functTest(data) {
            // populateChildrenElements(data)
            console.table(data)
        }

    </script>
<?php $v->end()?>