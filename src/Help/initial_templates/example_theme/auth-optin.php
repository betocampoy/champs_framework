<?php $v->layout("_theme"); ?>

<div class="container col-11 col-md-9" id="form-container">
    <div class="row align-items-center gx-5">
        <div class="col-md-6 order-md-2">
            <h2><?= $data->title; ?></h2>
            <p class=""><?= $data->desc; ?></p>
            <?php if (!empty($data->link)): ?>
                <div class="d-grid gap-2 col-6 mx-auto">
                    <a class="btn btn-secondary"
                       href="<?= $data->link; ?>" title="<?= $data->linkTitle; ?>"><?= $data->linkTitle; ?></a>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6 order-md-1">
            <div class="col-12">
                <img src="<?= $data->image ?>" alt="Hello New Customer"
                     class="img-fluid">
            </div>
        </div>
    </div>
</div>