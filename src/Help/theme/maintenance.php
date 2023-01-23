<?php
/** @var string $seo */
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
/** @var \BetoCampoy\ChampsFramework\View $v */
?>

<!doctype html>
<html lang="<?=CHAMPS_SYSTEM_LANGUAGE?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?=$seo?>

    <style>
        article{
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h2{
            padding: 10px;
        }

        p{
            padding: 10px;
        }
    </style>
</head>
<body>

<article>
    <h2><?=CHAMPS_MAINTENANCE_MODE_PAGE_TITLE?></h2>
    <img src="<?=CHAMPS_MAINTENANCE_MODE_PAGE_IMAGE?>" alt="Maintenance Image" height="400px">
    <p><?=CHAMPS_MAINTENANCE_MODE_PAGE_TEXT?></p>
</article>

</body>
</html>