<?php
/** @var string $title */
/** @var \BetoCampoy\ChampsFramework\View $v */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>

<!doctype html>
<html lang="br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?= $seo ?>

    <!-- Boot CSS -->
    <link rel="stylesheet"
          href="<?= theme("/assets/css/boot.css", CHAMPS_VIEW_APP) ?>">
    <!-- Fav icon-->
    <link rel="shortcut icon"
          href="<?= theme("/assets/images/favicon.ico", CHAMPS_VIEW_APP) ?>"
          type="image/x-icon">
    <!-- Google Fonts: Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
          rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor"
          crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
    <!-- Theme CSS -->
    <link rel="stylesheet"
          href="<?= help_theme("/assets/css/styles.css") ?>">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous" defer></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2"
            crossorigin="anonymous" defer></script>
    <!-- Core JS Theme -->
    <script src="<?= help_theme("/assets/js/scripts.js") ?>" defer></script>
</head>
<body>
<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">CHAMPSframwork</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                    class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?=url("/champs-docs/install")?>">Initial Setup</a></li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">Setup</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/install")?>">Installing</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/database")?>">Connecting MySql</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/constants")?>">Environment Constants</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">MVC</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/router")?>">Router</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/controller")?>">Controller Layer</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/model")?>">Model Layer</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/view")?>">View Layer</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                       data-bs-toggle="dropdown" aria-expanded="false">Features</a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/auth")?>">Authentication</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/minify")?>">Minify</a></li>
                        <li><a class="dropdown-item" href="<?=url("/champs-docs/maintenance-mode")?>">Maintenance Mode</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Page content-->
<div class="container">

    <div class="ajax_response"><?= flash() ?></div>

    <div class="ajax_load" style="z-index: 999;">
        <div class="ajax_load_box">
            <div class="ajax_load_box_circle"></div>
            <p class="ajax_load_box_title">Aguarde, carregando...</p>
        </div>
    </div>

    <div>
        <?= $v->section("content"); ?>
    </div>

    <div class='modal fade' tabindex='-1' role='dialog' id='modal-forms'>
        <div class='modal-dialog modal-lg' role='document'>
            <div class='modal-content'>
                <div class='modal-body'>
                    <div class='container' style='margin-left:40px; margin-top:10px'>
                        <div class="row justify-content-md-center">
                            <div class="col-sm-8 col-md-8 ">
                                <div class="panel panel-default" id='modal-forms-body'>

                                </div> <!-- /div do panel -->
                            </div> <!-- /div do col-->
                        </div> <!-- /div da 1st row-->
                    </div> <!-- /div do container-->
                </div>
            </div>
        </div>
    </div>

</div>

<footer class="portal-footer">
    <div class="col-sm-12 col-md-12">
        <p>&copy; <?= date('Y') ?> CHAMPSframework</p>
    </div>
</footer>

<?php if ($v->section("scripts")) {
    echo $v->section("scripts");
} ?>

</body>
</html>