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
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous" defer></script>

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

<div class="container-fluid">
    <header class="row" id="header">
        <div id="logo-container">
            <h1>CHAMPSframework</h1>
        </div>
        <p>Do <strong>better</strong> Do <strong>faster</strong></p>
    </header>
    <nav class="container" id="navbar">
        <div class="row justify-content-center">
            <a href="<?= url() ?>">Home</a>
            <a href="<?= url("/champs-docs") ?>">Documentation</a>
            <a href="<?= url("/terms") ?>">Terms</a>
            <a href="<?= url("/contact") ?>">Contato</a>
            <?php if (user()): ?>
                <a href="<?= url("/logout") ?>">Logout</a>
            <?php else: ?>
                <a href="<?= url("/login") ?>">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</div>

<?= $v->section("content"); ?>


<?= $v->section("scripts"); ?>

</body>
</html>