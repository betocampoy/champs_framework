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

        <p class="text-warnning">For more information, consult the <a href="#">documentation</a></p>
    </div>
</div>








