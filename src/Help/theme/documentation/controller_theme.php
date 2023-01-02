<?php
$v->layout("_theme");
?>

<?php $v->start("submenu");?>
<nav class="navbar navbar-expand-lg bg-light text-center mb-2" data-bs-theme="light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><spam class="d-lg-none">Controller Sub-menu</spam></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSecondary"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSecondary">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link 
                    <?= route()->path == '/champs-docs/controller' ? "active" : ""  ?>" 
                       href="<?=url("/champs-docs/controller")?>">Controller Layer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= route()->path == '/champs-docs/controller_auth' ? "active" : ""  ?>" 
                       href="<?=url("/champs-docs/controller_auth")?>">Authentication</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= route()->path == '/champs-docs/controller_validation' ? "active" : ""  ?>" 
                       href="<?=url("/champs-docs/controller_validation")?>">Inputs Validation</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link
                    <?= route()->path == '/champs-docs/controller_access_reports' ? "active" : ""  ?>"
                       href="<?=url("/champs-docs/controller_access_reports")?>">Access & Online Reports</a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<?php $v->end;?>

<?= $v->section("content"); ?>
