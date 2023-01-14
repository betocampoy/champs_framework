<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("widgets/databases/_databases_theme");
?>


<div class="card mb-3">
    <h2  class="card-header">Manage Database Connections</h2>
    <div class="card-body">
        <p>Connecting a MySQL Database at application</p>
        <ol class="list-group list-group-numbered mb-3">
            <li class="list-group-item">Create the Database Connection</li>
            <li class="list-group-item">Link the alias to DB connection. The default ALIAS is <strong>main</strong>, but you can use
            others, ex. audit, history, ...</li>
        </ol>

        <div class="alert alert-warning">For more information, about de Model Layer and how to execute database queries,
            consult the <a href="<?=CHAMPS_URL_DOCUMENTATION . "/model"?>" target="_blank">documentation</a></div>
    </div>
</div>








