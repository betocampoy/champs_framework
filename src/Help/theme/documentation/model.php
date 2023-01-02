<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("documentation/model_theme");
?>

<div class="card mb-3 text-center">
    <div class="card-header">
        <h5>Welcome to <strong>CHAMPS</strong>framework's documentation</h5>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Overview
    </div>
    <div class="card-body">
        <p class="card-text">CHAMPSframework is a PHP framework based in MVC arquitecture.</p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Main Features
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">MVC architecture</li>
            <li class="list-group-item">Router layer with friendly URLs</li>
            <li class="list-group-item">Model layer to simplify access to the MySql Database Server</li>
            <li class="list-group-item">View layer uses Plates Package</li>
            <li class="list-group-item">Controller Layer implements CSRF control and Inputs Validation</li>
            <li class="list-group-item">Message Object to standarize messages in all aplication</li>
            <li class="list-group-item">Session Object to standarize session manipulatin</li>
            <li class="list-group-item">Authentication based in Users, Roles and Permissions</li>
            <li class="list-group-item">and more ...</li>
        </ul>
    </div>
</div>

<div class="alert alert-warning" role="alert">
    <i class="bi bi-exclamation-triangle-fill"></i> It was developed by studying purposes only. DON'T USE IT IN PRODUCTION ENVIRONMENTS.
</div>
