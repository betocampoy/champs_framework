<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>Installing and configuring PHP CHAMPSframework</h1>
    <article class="body-item">
        <h2>1. Installing</h2>
        <p>CHAMPSframework is composer compatible, to install it, paste the line bellow in composer.json requered section</p>
        <p><code class="code" >"betocampoy/champs_framework": "^1.0"</code></p>
    </article>

    <article class="body-item">
        <h2>2. System file</h2>
        <p>Create the folder tree in root project needed by framework</p>
        <hr>
        <p><code>Source</code> : To store all project classes</p>
        <p><code>Source > App</code> : To store the controller classes</p>
        <p><code>Source > Boot</code> : To store the boot files</p>
        <p><code>Source > Models</code> : To store model classes</p>
        <p><code>Source > Support</code></p>
        <p><code>Source > Support > Excel</code> : To store classes that will control excel import</p>
        <p><code>Source > Support > MailTemplates</code> : Email templates</p>
        <p><code>Source > Support > Validator</code> : Inputs Validation classes</p>
        <hr>
        <p><code>Themes</code> : Root folder of the views. Create a sub folder for each theme (Ex. web, app, admin, email)</p>
    </article>

    <article class="body-item">
        <h2>3. Prepare boot files</h2>
        <p>Create a blank php file .</p>
    </article>

    <article class="body-item">
        <h2>4. Configure router</h2>
        <p>Create the .htaccess and the index.php in root folder.
            <br>to rewrite uri and activate navigation based in friendly URLs.</p>
        <p>Access <a href="<?=url("/router")?>">Router</a> for more information.</p>
    </article>

    <article class="body-item">
        <h2>5. Create the index.php file </h2>
        <p>Create the .htaccess in root folder to rewrite uri and activate navigation based in friendly URLs.</p>
        <p>Access <a href="<?=url("/router")?>">Router</a> for more information.</p>
    </article>

</article>
