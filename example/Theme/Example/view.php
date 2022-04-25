<?php
$v->layout("menu");
?>

<h1>Olá! eu sou sua view!</h1>
<p>Vamos começar?</p>
<?php session()->set("chave", "controller session") ?>
<?php session()->set("chave2", "controller session2") ?>

<?php var_dump(session()->all()) ?>
