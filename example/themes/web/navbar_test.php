<?php

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <?= $seo; ?>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript"
            src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js" defer></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous" defer></script>

</head>
<body>

<?php
$nav = (new BetoCampoy\ChampsFramework\Navbar\Bootstrap3())
    ->setNavbarItems("teste", "/teste")
    ->setNavbarItems("teste1", "/teste1")
    ->setNavbarItems("sub-teste2.1", "/teste1", null,"target='_blank'", 'teste1')
    ->setNavbarItems("sub-teste2.1.1", "/teste1", true,"target='_blank'", ["teste1","sub-teste2.1"])
    ->setNavbarItems("sub-teste2.1.2", "/teste1", true,"target='_blank'", ["teste1","sub-teste2.1"])
    ->setNavbarItems("teste2", "/teste2")
    ->setNavbarItems("teste3", "/teste1")
    ->setNavbarItems("teste4", "/teste1");


echo $nav->render();
?>

</body>

</html>
