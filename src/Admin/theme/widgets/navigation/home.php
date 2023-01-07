<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\Models\Navigation $navigations */
$v->layout("widgets/navigation/_nav_theme");
?>

<h4 class="card-title">Overview</h4>

<?php if(!$navigations->entityExists()):?>
    <div class="alert alert-warning mt-2 mb-2" role="alert">
        WARNING! There is a problem to access the database or the Navigation entity does not exists.
        <a href="<?=CHAMPS_URL_DOCUMENTATION.'navbar'?>" target="_blank" class="btn btn-link">Consult the documentation</a>
        for more information!
    </div>
<?php else:?>
    <div class="alert alert-success m-3" role="alert">
        The Navigation entity was create, so you can start managing your navbars!
    </div>
<?php endif;?>

<p class="card-text">Simplify the managing of your application's navbar with this feature.</p>
<p class="card-text">Register the navbars items and sub-items in database, their respective routes and permissions
    (if you are working with our <a href="<?=CHAMPS_URL_DOCUMENTATION.'auth'?>">auth class</a>) or just create
    the items using the navbar class methods.</p>
<p class="card-text">Use one of available templates, or use the default template and customize their CSS classes.</p>
<p class="card-text">It is even possible extend the template and customize it.</p>
<a href="<?=CHAMPS_URL_DOCUMENTATION.'navbar'?>" target="_blank" class="btn btn-primary">Go to the onlie documentation</a>






