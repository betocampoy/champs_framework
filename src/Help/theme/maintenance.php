<!doctype html>
<html lang="pt">
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
    <h2><?=CHAMPS_SYS_MAINTENANCE_PAGE_TITLE?></h2>
    <img src="<?=CHAMPS_SYS_MAINTENANCE_PAGE_IMG?>" alt="Maintenance Image" height="400px">
    <p><?=CHAMPS_SYS_MAINTENANCE_PAGE_TEXT?></p>
</article>

</body>
</html>