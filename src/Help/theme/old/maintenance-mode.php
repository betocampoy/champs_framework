<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>Maintenance Mode</h1>
    <article class="body-item">
        <h2>Description</h2>
        <p>When you need to put the system under maintenance mode, to avoid users to access it. All you have
            to do, configure or change some constants in <code>Source/Boot/Constants.php</code> file.
        </p>
    </article>
    <article class="body-item">
        <h2>Configuring and Activating Maintenance Mode</h2>
    </article>

    <article class="body-item">
        <h3>Activating and Deactivating maintenance mode</h3>
        <p><code>define("CHAMPS_SYS_UNDER_MAINTENANCE", true);</code> to activate</p>
        <p><code>define("CHAMPS_SYS_UNDER_MAINTENANCE", false);</code> to deactivate</p>
    </article>

    <article class="body-item">
        <h3>Configuring Excepitions</h3>
        <p>IP Addresses array that will allow to access application even when maintenance mode is activated for
            tests purposes.</p>
        <p><code>define("CHAMPS_SYS_MAINTENANCE_IP_EXCEPTIONS", []);</p>
    </article>

    <article class="body-item">
        <h3>Changing the default texts of maintenance mode page</h3>
        <p><code>define("CHAMPS_SYS_MAINTENANCE_PAGE_TITLE", "Your custom title");</p>
        <p><code>define("CHAMPS_SYS_MAINTENANCE_PAGE_TEXT", "Your custom text");</p>
        <p><code>define("CHAMPS_SYS_MAINTENANCE_PAGE_IMG", "/path/for/you/custom/image");</p>
    </article>

    <article class="body-item">
        <h3>Change the default maintenance route</h3>
        <p>If you need to customize all behavior of maintenance mode, you can change the default route, and customize
            the controller and the view.
        </p>
        <p>Ex. <code>define("CHAMPS_SYS_MAINTENANCE_ROUTE", "/custom_route");</p>
    </article>
</article>