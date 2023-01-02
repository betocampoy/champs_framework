<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="mit" content="2021-01-11T11:28:28-03:00+173172">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?= $seo; ?>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Icons only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&family=Unbounded:wght@200;600&display=swap"
          rel="stylesheet">

    <link rel="icon" type="image/png" href="<?= theme("/assets/images/favicon.png"); ?>"/>

    <?= renderLinksToMinifiedFiles("web"); ?>

</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<div class="container">
    <div class="ajax_response"><?= flash(); ?></div>
</div>

<!-- main nav -->
<div class="container-fluid">
    <header class="row" id="header">
        <div id="logo-container">
            <h1><strong>EXAMPLE</strong> theme</h1>
        </div>
        <p>Replace by your own theme</p>
    </header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary text-center">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= empty(route()->path) || route()->path == '/' ? "active" : "" ?>"
                           href="<?= url() ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" <?= route()->path == '/terms' ? "active" : "" ?>"
                        href="<?= url("/terms") ?>">Terms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" <?= route()->path == '/contact' ? "active" : "" ?>"
                        href="<?= url("/contact") ?>">Contact</a>
                    </li>
                    <?php if(user()):?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url("/logout") ?>">Logout</a>
                        </li>
                    <?php else:?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url("/login") ?>">Login</a>
                        </li>
                    <?php endif;?>

                </ul>
            </div>
        </div>
    </nav>
</div>

<div class="container" id="main-container-content">
    <?= $v->section("content"); ?>
</div>


<?= $v->section("scripts"); ?>

</body>
</html>