<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>CHAMPSframework Overview</h1>
    <article class="body-item">
        <h2>1. Description</h2>
        <p>CHAMPSframework is a PHP framework that implements core features such MVC structure, router, controller,
            model, authentication,
            inputs validation, assets minification, session as objects, ... all you have to do is configure the features
            and focus to
            develop you application.
        </p>
    </article>

    <article class="body-item">
        <h2>2. CHAMPSframework file system structure</h2>
        <p>It's important know and understand the structure to put the files in white places and insure the frameworks
            works as expected
        </p>
        <hr>
        <p><code>Source</code> : To store all project classes</p>
        <p><code>Source > App</code> : To store the controller classes</p>
        <p><code>Source > Boot</code> : To store the boot files.</p>
        <p><code>Source > Boot > Constants.php</code> : This file is called during framework initialization, you must
            define all application constants here.</p>
        <p><code>Source > Boot > CustomHelpers.php</code> : This file is called during framework initialization, Use it to
            implements the applicatin custom helpers </p>
        <p><code>Source > Support</code></p>
        <p><code>Source > Support > Validator</code> : Inputs Validation classes</p>
        <hr>
        <p><code>themes</code> : Root folder of the views. Create a sub folder for each theme (Ex. web, app, admin,
            email)</p>
        <hr>
        <p><code>shared</code> : Shared assets of project</p>
    </article>

</article>
