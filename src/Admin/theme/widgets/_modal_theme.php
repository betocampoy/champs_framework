<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigation */
/** @var array $theme_names */
?>
<div class="modal fade" id="champsModalId" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><?=$modal_title ?? ""?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <?= $v->section("content"); ?>

                </div>

                <?php if($v->section('modal-footer')):?>
                <div class="modal-footer">

                    <?=$v->section('modal-footer')?>

                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
