<?php
/** @var \Source\Models\User\User $users */
$v->layout("layout");
var_dump([$users->count(),$users->fetch(true)]);
foreach ($users->fetch(true) as $user){
    var_dump($user);
    echo "1".$user->email;
}
?>

<h1>Lista de usuários!</h1>
<p>Vamos começar?</p>

