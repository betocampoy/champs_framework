<?php
require __DIR__ . "/assets/config.php";
require __DIR__ . "/boot.php";
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

$session = new \BetoCampoy\ChampsSao\Session();
$session->destroy();

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
<h1>CHAMPS_SAO - EXAMPLE</h1>
<p>
    Menu :
    <a href="index.php">Exemplos</a> |
    <a href="add_values.php">Incluido Valores na Sessão</a> |
    <a href="destroy_session.php">Destruir Sessão</a>
</p>

<h4><strong>Operação: </strong>Recuperar todo o conteúdo da Sessão</h4>
<p>Comando: <code>$session->all()</code> <small>Nesse exemplo será utilizado o var_dump para exibir</small>
<p><small>Para incluir novos valores à sessão, acesse o menu [Incluido Valores na Sessão]</small></p>
<p><?php var_dump($session->all()); ?></p>


</body>
</html>