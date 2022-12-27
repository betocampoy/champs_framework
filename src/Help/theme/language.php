<?php
$v->layout("_layout")
?>

<article class="body-container">
    <h1>Change the Language of Framework Messages</h1>
    <article class="body-item">
        <h2>Description</h2>
        <p>By default, the CHAMPSframework messages are en English. But it's possible change the default language or
        replace for customized messages</p>
    </article>
    <article class="body-item">
        <h2>1. Define the system language</h2>
        <p>Define the constant <code>CHAMPS_FRAMEWORK_LANG</code> in /Source/Boot/Constants.php file.</p>
        <p>For example, <code>define("CHAMPS_FRAMEWORK_LANG", "pt-br");</code> to define the default language as Brazil Portuguese.</p>
    </article>
    <article class="body-item">
        <h2>2. Create the files with the translated messages</h2>
        <p>Create a new file at the same name of the value defined <code>CHAMPS_FRAMEWORK_LANG</code> in /Source/Support/Languages folder. In our example
        the file must be named as pt-br.php</p>
        <p>Copy an example file in vendor/betocampoy/champs_framework/src/Support/Languages.</p>
        <p>In the folder above, there is some translated files. Fill free to copy one of then and do you own translation.</p>
    </article>
</article>