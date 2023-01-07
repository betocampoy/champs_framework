<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
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
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&family=Unbounded:wght@200;600&display=swap" rel="stylesheet">

    <!-- JQuery CDN --><script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js' defer></script>
    <!-- JQuery Champs Engine Js --><script type='text/javascript'
                                            src='<?= __champsadm_theme("/../../Support/frontend/engine-jquery/engine-jquery.js?123456") ?>' defer></script>
    <!-- JQuery Champs Engine CSS --><link rel="stylesheet" href="<?=__champsadm_theme("/../../Support/frontend/engine-jquery/engine-jquery.css?123456")?>">

    <!-- Theme CSS --><link rel="stylesheet" href="<?=__champsadm_theme("/assets/css/styles.css?123")?>">
    <!-- Theme Js --><script type='text/javascript' src='<?= __champsadm_theme("/assets/js/scripts.js?123") ?>' defer></script>
    <!-- Favicon --><link rel="icon" type="image/png" href="<?= __champsadm_theme("/assets/images/favicon.ico"); ?>"/>

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
            <h1><strong>CHAMPS</strong>framework <small><?=isset($title) ? "- {$title}" : ''?></small></h1>
        </div>
        <p>Do <strong>better</strong> Do <strong>faster</strong></p>
    </header>
    <?=$navbar->render(current_route())?>
</div>


<div class="container" id="main-container-content">
    <?= $v->section("content"); ?>
</div>


<!-- FOOTER -->
<footer class="container mt-5">
    <p class="secondary-color"><strong>CHAMPS</strong>framework &copy;</p>
</footer>

<?= $v->section("scripts"); ?>

</body>
</html>