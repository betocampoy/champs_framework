<?php
/** @var string $sectionSelected */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");

function active(?string $needle = null, ?string $haystack = null){
//    if(!$needle) return 'btn-dark';

    return $haystack == $needle ? 'btn-dark' : 'btn-outline-dark';
}

?>

<div class="row vh-75 ">
<div class="col-md-4 vh-75">
    <div class="d-flex flex-column mx-5 alert border border-dark border-2 h-100">

        <div class="text-center m-2">
            <a class="btn  w-75 <?=active(null, $sectionSelected)?>"
               href="<?=url("/champsframework/parameters")?>">List All</a>
        </div>

        <?php foreach ($sections as $section): ?>
        <div class="text-center m-2">
            <a class="btn  w-75 <?=active($section, $sectionSelected)?>"
               href="<?=url("/champsframework/parameters?section={$section}")?>"><?=str_title($section)?></a>
        </div>
        <?php endforeach; ?>
    </div>
</div>



<div class="col">
    <?= $v->section("content"); ?>
</div>

</div>








