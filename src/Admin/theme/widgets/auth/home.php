<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("widgets/auth/_auth_theme");
?>

<div class="card mb-3">
    <h2  class="card-header">Manage Authentication Module</h2>
    <div class="card-body">
        <p>To activate the authentication module...</p>
        <ol class="list-group list-group-numbered mb-3">
            <li class="list-group-item">Connect the CHAMPSframework to a MySql Server.</li>
            <li class="list-group-item">Create the database tables to store users, roles, permissions and the many-to-many
                relationships. For more information about the database structure, consult the <a href="">documentation.</a></li>
            <li class="list-group-item">Once created, execute the script to create the initial data.</li>
            <li class="list-group-item">To manage the users, roles and permissions, use the Administrative Panel or develop
                your own custom admin.</li>
        </ol>

        <div class="alert alert-warning">For more information,
            consult the <a href="<?=CHAMPS_URL_DOCUMENTATION ."/auth"?>" target="_blank">documentation</a></div>
    </div>
</div>








