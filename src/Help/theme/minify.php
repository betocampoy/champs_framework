<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>Minify Configuration</h1>
    <article class="body-item">
        <h2>Description</h2>
        <p>CHAMPSframework has already implemented the package <code>matthiasmullie/minify</code> to
            performe theme's minification. To minify assets, first you define an array of configuration
            and than include the minified assets in the layout.</p>
        <p></p>
    </article>

    <article class="body-item">
        <h2>Configuring the CHAMPSframeworks to minify themes</h2>
        <p>Define in <code>Source/Boot/Constants.php</code> file the constant <strong>CHAMPS_MINIFY_THEMES</strong></p>
        <p>
            <code>
                define("CHAMPS_MINIFY_THEMES", [
                "themes" => [
                // informe the theme name, it also the dir name bellow theme dir
                "name_of_theme" => [
                "css" => [],
                "js" => [],
                "jquery-engine" => false,
                ]
                "name_of_theme1" => [
                "css" => [],
                "js" => [],
                "jquery-engine" => true,
                ]
                ]
                ]);
            </code>
        </p>
        <p>
        <ul>
            <li><b>name_of_theme</b> Name of the base theme forder, ex. app, admin, web, ... </li>
            <li><b>css</b> Array of priority css files. They will be minified in a separated files [asset/priority.css]
                preserving the array sequence. Informe the full file path, from base theme dir.
            </li>
            <li><b>js</b> Array of priority js files. They will be minified in a separated files [asset/priority.js]
                preserving the array sequence. Informe the full file path, from base theme dir.
            </li>
            <li><b>theme files</b> all files contained in assets/css and assets/js will be minified in
                [assets/theme.css] and [assets/theme.js].</li>
        </ul>
        </p>
    </article>

    <article class="body-item">
        <h2>Including minified files in the layout</h2>
        <p>To include the minified files, use the helper <code>renderLinksToMinifiedFiles("name_of_theme")</code>. </p>
    </article>

    <article class="body-item">
        <h2>Performing minification</h2>
        <p>For performance purposes, the minification only will be executed when you need. To do that,
            you access the url <code>/do-minify</code>
        </p>
    </article>
</article>