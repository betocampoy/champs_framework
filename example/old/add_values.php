<?php
require __DIR__ . "/assets/config.php";
require __DIR__ . "/boot.php";
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

$session = new \BetoCampoy\ChampsSao\Session();
if(isset($_GET['key']) && isset($_GET['value']) && !empty($_GET['key']) && !empty($_GET['value'])) {
    $session -> set($_GET['key'], $_GET['value']);
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Testing SAO Component</title>
</head>
<body>
<h1>CHAMPS_SAO - ADDING VALUES IN SESSION</h1>
<p>
    Menu :
    <a href="index.php">Exemplos</a> |
    <a href="add_values.php">Incluido Valores na Sessão</a> |
    <a href="destroy_session.php">Destruir Sessão</a>
</p>

<form action="" method="get">
    <label for="key">Key name</label>
    <input type="text" name="key" id="key">
    <label for="value">Value</label>
    <input type="text" name="value" id="value">
    <input type="submit" value="Save session">
</form>

<h4>Var_dump da sessao</h4>
<?php var_dump($session->all());?>

</body>
</html>


