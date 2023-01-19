<?php

$urlSetup = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$message = null;
$urlSetupSanit = __champs_sanit_url($urlSetup);

$checkInitSetup = (defined("CHAMPS_SYSTEM_SESSION_NAME")
    && !empty(CHAMPS_SYSTEM_SESSION_NAME) && defined("CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER")
    && in_array(CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER, ['DEV', 'PRD', 'UAT']));


$urlRegisteredSanit = !$checkInitSetup ? false : (defined("CHAMPS_SYSTEM_URL_" . CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER)
    ? __champs_sanit_url(constant("CHAMPS_SYSTEM_URL_" . CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER))
    : null);
$checkUrlAndEnv = strstr($urlSetupSanit, $urlRegisteredSanit) === false ? false : true;
if (!$checkInitSetup || !$checkUrlAndEnv) {
    /** start session */
    session_start();

    if (!empty($_POST)) {

        /* POST Variables */
        $urlRegistered = isset($_POST['CHAMPS_SYSTEM_URL']) && !empty($_POST['CHAMPS_SYSTEM_URL']) ? $_POST['CHAMPS_SYSTEM_URL'] : null;
        $sessionName = isset($_POST['CHAMPS_SYSTEM_SESSION_NAME']) && !empty($_POST['CHAMPS_SYSTEM_SESSION_NAME']) ? $_POST['CHAMPS_SYSTEM_SESSION_NAME'] : null;
        $csrfRequest = isset($_POST['csrf']) && !empty($_POST['csrf']) ? $_POST['csrf'] : null;
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $master_admin_email = isset($_POST['master_admin_email']) && !empty($_POST['master_admin_email']) ? $_POST['master_admin_email'] : null;
        $master_admin_password = isset($_POST['master_admin_password']) && !empty($_POST['master_admin_password']) ? $_POST['master_admin_password'] : null;
        $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;
        $confirm_password = isset($_POST['confirm_password']) && !empty($_POST['confirm_password']) ? $_POST['confirm_password'] : null;
        $csrfVerifyFail = !isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token']) || empty($csrfRequest) || $csrfRequest != $_SESSION['csrf_token'];
        $environment = isset($_POST['CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER']) && in_array($_POST['CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER'], ['DEV', 'PRD', 'UAT'])
            ? $_POST['CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER']
            : null;

        /** csrf verify */
        if ($csrfVerifyFail) {
            $message = "Invalid CSRF Token!";
        } /* Inputs validation */
        elseif (defined(CHAMPS_CONFIG_MASTER_ADMIN_EMAIL) && defined(CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD)
            && !empty(CHAMPS_CONFIG_MASTER_ADMIN_EMAIL) && !empty(CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD)
            && (!password_verify($master_admin_password, CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD)
                || $master_admin_email != CHAMPS_CONFIG_MASTER_ADMIN_EMAIL)) {
            $message = "The credentials informed are invalid!";
        }/* Inputs validation */
        elseif (!$environment || !$sessionName || !$urlRegistered) {
            $message = "The information entered are invalid or missing!";
        }/* save configuration */
        else {
            if (!empty(CHAMPS_CONFIG_MASTER_ADMIN_EMAIL) && (!$email || !$password || $password != $confirm_password)) {
                /* if there isn't credentials registered, check if passwords are equal and email was informed */
                $message = "The e-mail must be entered and the password and confirm password must be the same";
            } elseif (!empty(CHAMPS_CONFIG_MASTER_ADMIN_EMAIL) && !$email && $confirm_password && $password != $confirm_password) {
                /* if there is credentials registered, check if passwords are equal and email was informed to update */
                $message = "To change credentials, password and confirm password must be the same";
            } else {
                /* everything ok to save json */
                $newParameters = [];
                $newParameters['CHAMPS_SYSTEM_SESSION_NAME'] = strtoupper(str_slug($sessionName));
                $newParameters['CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER'] = $environment;
                $newParameters["CHAMPS_SYSTEM_URL_{$environment}"] = $urlRegistered;
                $newParameters["CHAMPS_CONFIG_MASTER_ADMIN_EMAIL"] =  strtolower($email);
                $newParameters["CHAMPS_CONFIG_MASTER_ADMIN_PASSWORD"] = password_hash($password, PASSWORD_BCRYPT);

                /** @var \BetoCampoy\ChampsFramework\Parameters\Definer $constantsDefiner*/
                $constantsDefiner->getConfigFile()->save($newParameters);
//                $fp = fopen(__CHAMPS_CONFIG_FILE__, 'w');
//                fwrite($fp, json_encode($parameters_data, JSON_PRETTY_PRINT));   // here it will print the array pretty
//                fclose($fp);

                header("Refresh:0");
                die();
            }
        }

    }

    if ($checkInitSetup && !$checkUrlAndEnv) {
        $urlRegistered = (defined("CHAMPS_SYSTEM_URL_" . CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER) ? constant("CHAMPS_SYSTEM_URL_" . CHAMPS_SYSTEM_ENVIRONMENT_IDENTIFIER) : 'URL NOT DEFINED');
        $message = $message ? $message : "Check the configuration, seams that the URL was changed. URL registered [{$urlRegistered}]";
    }

    include __VENDOR_DIR__ . "/src/Admin/initial_setup.php";
    die();
}
