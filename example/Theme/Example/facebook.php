<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>Login with Facebook</h1>

    <?php if(session()->authUser):?>
        <h4>Usuario logado</h4>
    <?php else:?>
        <h4>Usuario não logado</h4>
    <?php endif?>

</article>
