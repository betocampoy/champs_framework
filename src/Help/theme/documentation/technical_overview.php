<?php
/** @var \BetoCampoy\ChampsFramework\Router\Router $router */
$v->layout("_theme");
?>

<div class="card mb-3 text-center">
    <div class="card-header">
        <h5>Techinical Overview</h5>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Description
    </div>
    <div class="card-body">
        <p class="card-text">
            CHAMPSframework is a PHP framework that implements core features such MVC structure, router, controller,
            model, authentication,
            inputs validation, assets minification, session as objects, ... all you have to do is configure the features
            and focus to
            develop you application.
        </p>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        Required root files
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">composer.json</div>
                    Configuration file of composer (PHP Dependencies Manager).
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">.htaccess</div>
                    Server configuration file used to rewrite.
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">index.php</div>
                    Contain all routes of project.
                </div>
            </li>
        </ul>
    </div>

</div>

<div class="card mb-3">
    <div class="card-header">
        File system structure
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">/Source</div>
                    Root repository for all project classes and configuration files.

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">/App</div>
                                Repository for <strong>Controller</strong> classes.
                            </div>
                        </li>
                    </ul>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">/Boot</div>
                                Repository for boot and configuration files.
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">Constants.php</div>
                                            This file is called during framework initialization, you must
                                            define all application constants here.
                                        </div>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">CustomHelpers.php</div>
                                            This file is called during framework initialization, Use it to
                                            implements the applicatin custom helpers.
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">/Support</div>
                                Support framework classes.

                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div class="ms-2 me-auto">
                                            <div class="fw-bold">/Validators</div>
                                            Inputs Validation classes. Consult <a href="#">validators</a> for more
                                            informations.
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">/Model</div>
                                Repository for <strong>Model</strong> classes.
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">/theme</div>
                    Root folder of the views. Create a sub folder for each theme (Ex. web, app, admin,
                    email).
                </div>
            </li>
        </ul>
    </div>

</div>

