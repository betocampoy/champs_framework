<?php

function convertToString($value):string
{
    if(!is_array($value)){
        return $value ?? '';
    }

    $newValue = '';
    foreach ($value as $key => $item){
        $newValue .= is_int($key)
            ? (!$newValue ? $item : ";{$item}")
            : (!$newValue ? "{$key}={$item}" : ";{$key}={$item}");
    }
    return $newValue;

}

$lastSection = null;
$sectionIsOpen = false;
$v->layout("widgets/parameters/_parameters_theme");

?>

<div class="bg-dark text-white">
    <h3 class="text-white bg-dark p-2 text-center">Manage the framework's parameters</h3>
</div>

<div class="card" id="top">
    <div class="card-body">
        <form action="post">
            <fieldset>
                <?php foreach ($parametersBySection as $section => $parameters): ?>

                    <div class='card mb-3'>
                        <div class='card-header bg-dark text-white d-flex justify-content-between' id="<?=$section?>">
                            <span><h5><?=$section?></h5></span>
                            <div><a class="text-white" href="#top"><i class="bi bi-arrow-up-circle"></i> Back</a></div>
                        </div>

                        <div class='card-body'>

                            <?php foreach ($parameters as $constantName => $parameter): ?>

                                <!-- input fields -->
                                <?php if ($parameter['inputType'] == 'select'): ?>
                                    <div class="form-floating mb-3">
                                        <select class="form-select" aria-label="<?= $constantName ?>"
                                                name="<?= $constantName ?>"
                                                id="<?= $constantName ?>">
                                            <option value="" disabled selected>Select one option</option>
                                            <?php foreach ($parameter['validValues'] as $title => $value): ?>
                                                <option value="<?= $value ?>" <?= option_is_selected($parameter['value'], $value) ?>><?= $title ?></option>
                                            <?php endforeach; ?>

                                        </select>
                                        <label for="<?= $constantName ?>"
                                               class="form-label"><?= $constantName ?></label>
                                        <div id="<?= $constantName ?>_help" class="form-text">
                                            <?= $parameter['inputAttributes']['help'] ?? '' ?>
                                        </div>
                                    </div>
                                <?php elseif (in_array($parameter['inputType'], ['text', 'email', 'password'])): ?>
                                    <div class="form-floating mb-3">
                                        <input type="<?= $parameter['inputType'] ?>" class="form-control"
                                               id="<?= $constantName ?>"
                                               value="<?= convertToString($parameter['value']) ?>"
                                               name="<?= $constantName ?>" placeholder="<?= $constantName ?>">
                                        <label for="<?= $constantName ?>"
                                               class="form-label"><?= $constantName ?></label>
                                        <div id="<?= $constantName ?>_help"
                                             class="form-text">
                                            <?= $parameter['inputAttributes']['help'] ?? '' ?>
                                        </div>
                                    </div>
                                <?php elseif ($parameter['inputType'] == 'switch'): ?>
                                    <div class="form-control mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input"
                                                   type="checkbox" <?= $parameter['value'] ? 'checked' : '' ?>
                                                   role="switch" name="<?= $constantName ?>" id="<?= $constantName ?>">
                                            <label class="form-check-label"
                                                   for="<?= $constantName ?>"><?= $constantName ?></label>
                                        </div>
                                        <div id="<?= $constantName ?>_help" class="form-text">
                                            <?= $parameter['inputAttributes']['help'] ?? '' ?>
                                        </div>
                                    </div>
                                <?php elseif ($parameter['inputType'] == 'hidden'): ?>

                                    <input type="hidden" class="form-control"
                                           id="<?= $constantName ?>"
                                           value="<?= $parameter['value'] ?>"
                                           name="<?= $constantName ?>">

                                <?php endif; ?>


                            <?php endforeach; ?>

                        </div>
                    </div>

                <?php endforeach; ?>

                <button type="button"
                    <?= csrf_data_attr() ?>
                        data-send_inputs="true"
                        data-section_group="<?=$sectionSelected?>"
                        data-post="<?= $router->route("champs.admin.parametersSave") ?>"
                        class="btn btn-primary sendForm">Save changes
                </button>

            </fieldset>
        </form>
    </div>
</div>