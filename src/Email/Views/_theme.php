<?php
/** @var string $title */
/** @var \BetoCampoy\ChampsFramework\Email\EmailView $v */
?>

<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title><?= $title ?></title>
    <style>
        body {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            font-family: Helvetica, sans-serif;
        }

        table {
            max-width: 500px;
            padding: 0 10px;
            background: #ffffff;
        }

        .content {
            font-size: 16px;
            margin-bottom: 25px;
            padding-bottom: 5px;
            border-bottom: 1px solid #EEEEEE;
        }

        .content p {
            margin: 25px 0;
        }

        .btn {
            padding: 10px 20px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border: none;
            border-radius: 10px;
        }

        .btn-primary{
            background-color: blue;
            color: white !important;
            text-decoration: none;
        }

        .footer {
            font-size: 14px;
            color: #888888;
            font-style: italic;
        }

        .footer p {
            margin: 0 0 2px 0;
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>
            <div class="content">
                <?= $v->section("content"); ?>
                <p>Atenciosamente, equipe <?= CHAMPS_SITE_NAME; ?>.</p>
            </div>
            <div class="footer">
                <p><?= CHAMPS_SITE_NAME; ?> - <?= CHAMPS_SITE_TITLE; ?></p>
                <p><?= CHAMPS_SITE_ADDR_STREET; ?>
                    , <?= CHAMPS_SITE_ADDR_NUMBER; ?><?= (CHAMPS_SITE_ADDR_COMPLEMENT ? ", " . CHAMPS_SITE_ADDR_COMPLEMENT : ""); ?></p>
                <p><?= CHAMPS_SITE_ADDR_CITY; ?>/<?= CHAMPS_SITE_ADDR_STATE; ?> - <?= CHAMPS_SITE_ADDR_ZIPCODE; ?></p>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
