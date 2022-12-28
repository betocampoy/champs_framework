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

                    <card class="card border-secondary m-3">
                        <div class="card-body">
                        <pre><code>
{
    "authors": [
        {
            "name": "Creator Author Name",
            "email": "author@email.com",
            "homepage": "url.of.project",
            "role": "Developer"
        }
    ],
    "description": "Description of you project",
    "config": {"vendor-dir": "vendor"},
    "autoload": {
        "psr-4": {"Source\\": "Source/"}
    },
    "require": {
        "php": "^7.4",
        "betocampoy/champs_framework": "1.0.*"
    }
}
</code></pre>
                        </div>
                    </card>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">.htaccess</div>
                    Server configuration file used to rewrite.

                    <card class="card border-secondary m-3">
                        <div class="card-body">
                        <pre><code>
RewriteEngine On
Options All -Indexes

## ROUTER WWW Redirect.
#RewriteCond %{HTTP_HOST} !^www\. [NC]
#RewriteRule ^ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

## ROUTER HTTPS Redirect
#RewriteCond %{HTTP:X-Forwarded-Proto} !https
#RewriteCond %{HTTPS} off
#RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# ROUTER URL Rewrite
RewriteCond %{SCRIPT_FILENAME} !-f
RewriteCond %{SCRIPT_FILENAME} !-d
RewriteRule ^(.*)$ index.php?route=/$1 [L,QSA]
</code></pre>
                        </div>
                    </card>
                </div>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-start">
                <div class="ms-2 me-auto">
                    <div class="fw-bold">index.php</div>
                    Contain all routes of project.

                    <card class="card border-secondary m-3">
                        <div class="card-body">
                        <pre><code>
ob_start();
date_default_timezone_set('America/Sao_Paulo');
require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use BetoCampoy\ChampsFramework\Session;
use BetoCampoy\ChampsFramework\Router\Router;
use function ICanBoogie\pluralize;

$session = new Session();
$route = new Router(url(), ":");
$route->namespace("Source\App");

/**
 * EXAMPLE THEME ROUTES
 */
$route->group(null);
$route->get("/", "WebExample:home");
$route->get("/terms", "WebExample:terms");
$route->get("/contact", "WebExample:contact");

/**
 * CREATE YOUR CUSTOM ROUTES BELOW
 */


/**
 * CREATE YOUR CUSTOM ROUTES ABOVE
 */

/**
 * ROUTE DISPATCH
 */
$route->dispatch();


/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect( $route->route("default.error", ["errcode" => $route->error()]));
}

ob_end_flush();
</code></pre>
                        </div>
                    </card>
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

