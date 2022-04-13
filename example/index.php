<?php
require __DIR__ . "/assets/config.php";
require __DIR__ . "/boot.php";
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
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
<h1>CHAMPS_SAO - EXAMPLE</h1>
<p>
    Menu :
    <a href="index.php">Exemplos</a> |
    <a href="add_values.php">Incluido Valores na Sessão</a> |
    <a href="destroy_session.php">Destruir Sessão</a>
</p>

<h4><strong>Operação: </strong>Criando a sessão</h4>
<p>Comando: $session = new Session()</p>
<?php $session = new \BetoCampoy\ChampsSao\Session();?>

<h4><strong>Operação: </strong>Gravando valores na sessao</h4>
<p>Comando: $session->set("key_name1", "key value1")</p>
<p>Comando: $session->set("key_name2", "key value2")</p>
<?php $session -> set("key_name1", "key value1"); ?>
<?php $session -> set("key_name2", "key value2"); ?>

<h4><strong>Operação: </strong>Recuperando o valor da sessao</h4>
<p>Comando: <code>$session->key_name1</code> : Valor recuperado [<?= $session->key_name1; ?>]</p>
<p>Comando: <code>$session->key_name2</code> : Valor recuperado [<?= $session->key_name2; ?>]</p>

<h4><strong>Operação: </strong>Apagar valor da sessão</h4>
<p>Comando: <code>$session->unset('key_name1')</code></p>
<?php $session->unset('key_name1');?>


<h4><strong>Operação: </strong>Verificar se chave existe</h4>
<p>Comando: <code>$session->has('key_name1')</code> : Resultado [<?=$session->has("key_name1")?>]
    <small><strong>Mão existe pois foi apagado no exemplo anterior</strong></small></p>
<p>Comando: <code>$session->has('key_name2')</code> : Resultado [<?=$session->has("key_name2")?>]</p>

<h4><strong>Operação: </strong>Helpers disponíveis</h4>
<li>sao_csrf_data_attr();</li>
<li>sao_csrf_input();</li>
<li>sao_csrf_verify();</li>
<li>sao_flash();</li>
<li>sao_request_limit();</li>
<li>sao_request_repeat();</li>

<h4><strong>Operação: </strong>Recuperar todo o conteúdo da Sessão</h4>
<p>Comando: <code>$session->all()</code> <small>Nesse exemplo será utilizado o var_dump para exibir</small>
<p><small>Para incluir novos valores à sessão, acesse o menu [Incluido Valores na Sessão]</small></p>
<p><?php var_dump($session->all()); ?></p>

<h4><strong>Operação: </strong>Destruir a sessão</h4>
<p>Comando: <code>$session->destroy()</code>
<p>
    <?php $session->destroy();
    var_dump($session->all());
    ?>
</p>

</body>
</html>