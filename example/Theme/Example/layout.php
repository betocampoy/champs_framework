<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Example Controller</title>

    <?//= $seo; ?>
    
</head>
<body>
<h1>CHAMPSframework</h1>
<p>
    <a href="<?=url("clousure")?>">Listar Sessão</a> -
    <a href="<?=url("/sao_destroy")?>">Destruir Sessão</a> -
</p>

<?= $v->section("content"); ?>


</body>
</html>