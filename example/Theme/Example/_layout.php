<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="<?=url("/assets/style.css")?>">

    <?= $seo; ?>

    <style>
        .body-container {
            margin: 20px 0;
            padding: 5px;
            background-color: lightgray;
        }

        .body-container > h1, .body-item {
            margin: 10px;
            padding: 5px;
        }

        .body-item {
            background: white;
        }

        .body-item > h2, p {
            margin: 4px;
            font-size: 95%;
            line-height: 25px;
        }

        .code{
            font-size: 150%;
            padding: 20px;
            margin: 20px;
        }
    </style>
</head>
<body>


<div class='container'>

    <h1>CHAMPSframework</h1>

    <div class='menu-button'>Menu</div>
    <ul class='flexnav' data-breakpoint='800'>
        <li><a href="<?=url()?>">Home</a></li>

        <li>
            <a href='#'>Starting Here</a>
            <ul>
                <li><a href="<?=url("/install")?>">Install</a></li>
                <li><a href="<?=url("/boot")?>">Boot Files</a></li>
                <li><a href="<?=url("/constants")?>">Constants</a></li>
            </ul>
        </li>

        <li>
            <a href='#'>MVC</a>
            <ul>
                <li><a href="<?=url("/router")?>">Router</a></li>
                <li><a href="<?=url("/controller")?>">Controller</a></li>
                <li><a href="<?=url("/model")?>">Model</a></li>
            </ul>
        </li>

        <li>
            <a href='#'>Other Features</a>
            <ul>
                <li><a href="<?=url("/session")?>">Sessions</a></li>
                <li><a href="<?=url("/authentication")?>">Authentication</a></li>
                <li><a href="<?=url("/validation")?>">Inputs Validation</a></li>
                <li><a href="<?=url("/csrf")?>">CSRF Control</a></li>
            </ul>
        </li>

        <li>
            <a href='#'>Frontend Features</a>
            <ul>
                <li><a href="<?=url("/navigation")?>">Navigation</a></li>
                <li><a href="<?=url("/seo")?>">SEO</a></li>
                <li><a href="<?=url("/messages")?>">Messages</a></li>
                <li><a href="<?=url("/jquery-engine")?>">Engine Jquery</a></li>
            </ul>
        </li>

    </ul>



    <?= $v->section("content"); ?>


    <script type="text/javascript" src="<?=url("/assets/jquery.min.js")?>"></script>
    <script type="text/javascript" src="<?=url("/assets/jquery.flexnav.min.js")?>"></script>
    <script>
        $(".flexnav").flexNav();
    </script>
</div>
</body>
</html>