<?php
require __DIR__ . "/assets/config.php";
require __DIR__ . "/boot.php";
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

echo "<h3>Acessando a sessao como objeto</h3>";

echo "<h4>Criando a sessão</h4>";
echo "<p>\$session = new Session()</p>";
$session = new \BetoCampoy\ChampsSao\Session();

echo "<h4>Gravando um valor na sessao</h4>";
echo "<p>\$session->set(\"chave\", \"valor da chave\")</p>";
if(isset($_GET['key']) || isset($_GET['value'])){
    $session -> set($_GET['key'], $_GET['value']);
}else {
    $session -> set("chave", "valor da chave");
}

echo "<h4>Recuperando o valor da sessao</h4>";
echo "<p>\$session->chave</p>";
echo "<p>Saída: $session->chave</p>";

echo "<h4>Var_dump da sessao</h4>";
var_dump(session()->all());
