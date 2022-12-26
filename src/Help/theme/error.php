<!doctype html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <?=$seo?>

    <style>

        body {
            font-family: 'Montserrat',sans-serif;
        }

        p {
            margin: 20px 0 0 0;
        }

        .not_found{
            text-align: center;
            color: #555;
        }

        .content {
            padding: 60px 0;
        }

        .content, .container {
            display: block;
            width: 1200px;
            max-width: 90%;
            margin: 0 auto;
        }

        .not_found_header {
            width: 500px;
            max-width: 100%;
            margin: 0 auto;
        }

        .not_found .error {
            font-size: 8em;
            font-weight: var(--weight-light);
            color: #ccc;
            margin-bottom: 40px;
        }

        .error{
            font-size: 8em;
            font-weight: var(--weight-light);
            color: #ccc;
            margin-bottom: 40px;
            /*font-weight: bold;*/
            /*font-size: xxx-large;*/
            /*color: darkslategray;*/

        }

        .not_found_btn {
            display: inline-block;
            margin-top: 60px;
            padding: 20px 40px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            background-color: black;
            border-radius: 8px;
        }


    </style>
</head>
<body>

<main class="main_content">
    <article class="not_found">
        <div class="container content">
            <header class="not_found_header">
                <p class="error">&bull;<?= $error->code; ?>&bull;</p>
                <h1><?= $error->title; ?></h1>
                <p><?= $error->message; ?></p>

                <?php if ($error->link): ?>
                    <a class="not_found_btn"
                       title="<?= $error->linkTitle; ?>" href="<?= $error->link; ?>"><?= $error->linkTitle; ?></a>
                <?php endif; ?>
            </header>
        </div>
    </article>
</main>

</body>
</html>