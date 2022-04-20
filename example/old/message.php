<?php
require __DIR__ . "/assets/config.php";
require __DIR__ . "/boot.php";
if(strpos(__DIR__, 'vendor')){
    require __DIR__ . "/../../../../vendor/autoload.php";
}else{
    require __DIR__ . "/../vendor/autoload.php";
}

require __DIR__ . "/../src/Message.php";

echo "<h4>Testar as Mensagens</h4>";

$msg = new \BetoCampoy\ChampsMessages\Message();

echo $msg->error("This is an error message")->render();
echo $msg->warning("This is an WARNING message")->render();
echo $msg->success("This is a SUCCESS message")->render();
echo $msg->info("This is an INFORMATION message")->render();
echo $msg->error("This is a message stored in flash session")->flash();

// to get the flash message from session
$sess = new \BetoCampoy\ChampsSao\Session();
echo $sess->flash();