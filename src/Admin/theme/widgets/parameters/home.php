<?php
$lastSection = null;
$sectionIsOpen = false;
$v->layout("_theme");

function sectionChanged($parameter, $lastSection, $sectionIsOpened){
    $title = strtoupper($parameter['section'])." PARAMETERS";
    if ($lastSection != $parameter['section'] && !$sectionIsOpened){
        return "<div class='card mb-3'><div class='card-header'><h5>{$title}</h5></div><div class='card-body'>";
    }elseif ($lastSection != $parameter['section'] && $sectionIsOpened){
        return "</div></div><div class='card mb-3'><div class='card-header'><h5>{$title}</h5></div><div class='card-body'>";
    }
    return '';
}

?>

<div class="bg-dark text-white">
    <h3 class="text-white bg-dark p-2 text-center">Manage the framework's parameters</h3>
</div>

<div class="card">
    <div class="card-body">
        <form action="post">
            <fieldset>
                <?php foreach ($parameters as $constantName => $parameter): ?>

                    <?=sectionChanged($parameter, $lastSection, $sectionIsOpen)?>

                    <?php $lastSection = $parameter['section']; $sectionIsOpen = true;?>

<!-- input fields -->
                    <?php if ($parameter['type'] == 'select'): ?>
                        <div class="form-floating mb-3">
                            <select class="form-select" aria-label="<?= $constantName ?>" name="<?= $constantName ?>"
                                    id="<?= $constantName ?>">
                                <option value="" disabled selected>Select one option</option>
                                <?php foreach ($parameter['possible_values'] as $title => $value): ?>
                                    <option value="<?= $value ?>" <?= option_is_selected($parameter['value'], $value) ?>><?= $title ?></option>
                                <?php endforeach; ?>

                            </select>
                            <label for="<?= $constantName ?>" class="form-label"><?= $constantName ?></label>
                            <div id="<?= $constantName ?>_help" class="form-text">
                                <?= $parameter['help_message'] ?>
                            </div>
                        </div>
                    <?php elseif (in_array($parameter['type'], ['text', 'email', 'password'])): ?>
                        <div class="form-floating mb-3">
                            <input type="<?= $parameter['type'] ?>" class="form-control" id="<?= $constantName ?>"
                                   value="<?= $parameter['value'] ?>"
                                   name="<?= $constantName ?>" placeholder="<?= $constantName ?>">
                            <label for="<?= $constantName ?>" class="form-label"><?= $constantName ?></label>
                            <div id="<?= $constantName ?>_help"
                                 class="form-text"><?= $parameter['help_message'] ?></div>
                        </div>
                    <?php elseif ($parameter['type'] == 'switch'): ?>
                <div class="form-control mb-3">
                    <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" <?= $parameter['value'] ? 'checked' : '' ?>
                                   role="switch" id="<?= $constantName ?>">
                            <label class="form-check-label" for="<?= $constantName ?>"><?= $constantName ?></label>
                        </div>
                        <div id="<?= $constantName ?>_help" class="form-text">
                            <?= $parameter['help_message'] . " " .$parameter['value']?>
                        </div>
                </div>
                    <?php endif; ?>


                <?php endforeach; ?>


            </fieldset>
        </form>
    </div>
</div>