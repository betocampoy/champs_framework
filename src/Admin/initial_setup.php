<?php
/** @var string $urlSetup */
/** @var string $message */
/** @var array $parameters_data */
$_SESSION['csrf_token'] = md5(uniqid(rand(), true));
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="mit" content="2021-01-11T11:28:28-03:00+173172">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Bootstrap Icons only -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous" defer></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fugaz+One&family=Unbounded:wght@200;600&display=swap"
          rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= __VENDOR_DIR__ . "/src/Admin/theme/assets/images/favicon.ico" ?>"/>

</head>
<body>

<div class="container" id="main-container-content">
    <div class="bg-dark text-white">
        <h1 class="text-white bg-dark p-2 text-center"><strong>CHAMPSframework</strong> initial setup</h1>
    </div>

    <?php if (isset($message) && !empty($message)): ?>
        <div class="alert alert-danger" role="alert">
            <?= $message ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            WARNING: You must setup the parameter below to start the framework.
        </div>
    <?php endif; ?>

    <div class="card mb-2">
        <div class="card-header">
            <h4>Configuring the environment</h4>
        </div>
        <div class="card-body">
            <form action="" method="post">
                <fieldset>
                    <input type="hidden" class="form-control" id="csrf" value="<?= $_SESSION['csrf_token'] ?>"
                           name="csrf">

                    <?php if (!empty(CHAMPS_CONFIG_MASTER_ADMIN_EMAIL)): ?>
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6>To update settings, you must enter the current user and password</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="master_admin_email"
                                           value=""
                                           name="master_admin_email"
                                           placeholder="Enter the e-mail registered during initial setup">
                                    <label for="master_admin_email" class="form-label">Enter the e-mail registered
                                        during initial setup</label>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="master_admin_password"
                                           value=""
                                           name="master_admin_password"
                                           placeholder="Enter the password registered during initial setup">
                                    <label for="master_admin_password" class="form-label">Enter the password registered
                                        during initial setup</label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="CHAMPS_SYSTEM_URL"
                               value="<?= $urlSetup ?>"
                               name="CHAMPS_SYSTEM_URL" placeholder="Enter the URL project for environment">
                        <label for="CHAMPS_SYSTEM_URL" class="form-label">Enter the URL project for environment</label>
                        <div id="CHAMPS_SYSTEM_URL_help" class="form-text">
                            This parameter can be set manually defining the constant [CHAMPS_SYSTEM_URL_XXX]. Where XXX refers
                            to environment.
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <select class="form-select" aria-label="Define the environment"
                                name="CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER"
                                id="CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER">
                            <option selected disabled>Select the environment?</option>
                            <option value="DEV" <?= option_is_selected(CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER ?? '', "DEV") ?>>
                                Development
                            </option>
                            <option value="UAT" <?= option_is_selected(CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER ?? '', "UAT") ?>>
                                Test
                            </option>
                            <option value="PRD" <?= option_is_selected(CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER ?? '', "PRD") ?>>
                                Production
                            </option>
                        </select>
                        <label for="CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER" class="form-label">Define the environment?</label>
                        <div id="CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER_help" class="form-text">
                            This parameter can be set manually defining the constant [CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER]
                        </div>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="CHAMPS_SYSTEM_SESSION_NAME"
                               value="<?= CHAMPS_SYSTEM_SESSION_NAME ?>"
                               name="CHAMPS_SYSTEM_SESSION_NAME" placeholder="Enter a unique name for session">
                        <label for="CHAMPS_SYSTEM_SESSION_NAME" class="form-label">Enter a unique name for session</label>
                        <div id="CHAMPS_SYSTEM_SESSION_NAME_help" class="form-text">
                            This parameter can be set manually defining the constant [CHAMPS_SYSTEM_SESSION_NAME]
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h6>Master Admin Credentials</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email"
                                       value="<?= CHAMPS_CONFIG_MASTER_ADMIN_EMAIL ?? '' ?>"
                                       name="email" placeholder="Enter one e-mail to become the master administrator">
                                <label for="email" class="form-label">Enter one e-mail to become the master
                                    administrator</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="password"
                                       value=""
                                       name="password" placeholder="Enter the password of master administrator user">
                                <label for="password" class="form-label">Enter the password of master administrator
                                    user</label>
                            </div>

                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" id="confirm_password"
                                       value=""
                                       name="confirm_password" placeholder="Confirm the password">
                                <label for="confirm_password" class="form-label">Confirm the password</label>

                            </div>

                            <div class="alert alert-warning" role="alert">
                                Important: This credentials are used for manage the framework parameters until activated the authentication. We strongly recommend
                                activate it as soon as possible!
                            </div>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save changes</button>
                </fieldset>
            </form>
        </div>
    </div>

</div>

<!-- FOOTER -->
<footer class="container mt-5 text-center">
    <p class="secondary-color"><strong>CHAMPS</strong>framework &copy;</p>
</footer>

</body>
</html>
