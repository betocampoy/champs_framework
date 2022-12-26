<?php


if (!defined("__CHAMPS_DIR__")) define("__CHAMPS_DIR__", str_replace("\\vendor\\betocampoy\\champs_framework", "", __DIR__));
if (!defined("__CHAMPS_THEME_DIR__")) define("__CHAMPS_THEME_DIR__", __CHAMPS_DIR__ . "/themes");
$baseDir = fullpath();

// Inicialize Defined Constants
if (file_exists(__CHAMPS_DIR__."/Source/Boot/Constants.php")) {
    require __CHAMPS_DIR__."/Source/Boot/Constants.php";
}

// Inicialize Application Custom Helpers
if (file_exists(__CHAMPS_DIR__."/Source/Boot/CustomHelpers.php")) {
    require __CHAMPS_DIR__."/Source/Boot/CustomHelpers.php";
}

if(defined("CHAMPS_SYS_BOOT_FILES") && is_array(CHAMPS_SYS_BOOT_FILES)){
    foreach (CHAMPS_SYS_BOOT_FILES as $file){
        $file = (strtolower(substr($file, -3, 3)) == "php") ? $file : $file."php";
        if(file_exists(__CHAMPS_DIR__."/$file")){
            include __CHAMPS_DIR__."/{$file}";
        }
    }
}

if(!defined("CHAMPS_SYS_ENCODING")) define("CHAMPS_SYS_ENCODING", "UTF-8");
if (!defined("CHAMPS_FRAMEWORK_LANG")) define("CHAMPS_FRAMEWORK_LANG", "en");
$language = strtolower(CHAMPS_FRAMEWORK_LANG);
if(file_exists(__CHAMPS_DIR__."/Source/Support/Languages/{$language}.php")){
    include_once __CHAMPS_DIR__."/Source/Support/Languages/{$language}.php";
}
if (!defined("CHAMPS_FRAMEWORK_MESSAGES")) define("CHAMPS_FRAMEWORK_MESSAGES", []);

/* create the environment storage tree from framework*/
// STORAGE
full_folder_path("shared");
full_folder_path("Source/App");
full_folder_path("Source/Boot");
full_folder_path("Source/Models");
full_folder_path("Source/Support/Validators");
full_folder_path("Source/Support/MailTemplates");
full_folder_path("themes");

/* CREATING THE EXAMPLE THEME */
// Web example controller
if(!file_exists(__CHAMPS_DIR__."/Source/App/WebExample.php") && !is_file(__CHAMPS_DIR__."/Source/App/WebExample.php")){
    copy(__CHAMPS_DIR__."/vendor/betocampoy/champs_framework/src/Help/initial_templates/WebExample.php"
        , __CHAMPS_DIR__."/Source/App/WebExample.php");
}
// Web Example Theme
if(!file_exists(__CHAMPS_DIR__."/themes/web") && !is_dir(__CHAMPS_DIR__."/themes/web")) {
    copyr(__CHAMPS_DIR__."/vendor/betocampoy/champs_framework/src/Help/initial_templates/example_theme",
        __CHAMPS_THEME_DIR__."/web");
}

full_folder_path("themes/web");
full_folder_path("themes/admin");
full_folder_path("themes/app");
full_folder_path("themes/opr");
full_folder_path("themes/email");

// Initial Constants.php file
if(!file_exists(__CHAMPS_DIR__."/Source/Boot/Constants.php") && !is_file(__CHAMPS_DIR__."/Source/Boot/Constants.php")){
    copy(__CHAMPS_DIR__."/vendor/betocampoy/champs_framework/src/Help/initial_templates/Constants.php"
        , __CHAMPS_DIR__."/Source/Boot/Constants.php");
}

// Initial CustomHelpers.php file
if(!file_exists(__CHAMPS_DIR__."/Source/Boot/CustomHelpers.php") && !is_file(__CHAMPS_DIR__."/Source/Boot/CustomHelpers.php")){
    copy(__CHAMPS_DIR__."/vendor/betocampoy/champs_framework/src/Help/initial_templates/CustomHelpers.php"
        , __CHAMPS_DIR__."/Source/Boot/CustomHelpers.php");
}

//// Initial .htaccess file
//if(!file_exists(__CHAMPS_DIR__."/.htaccess") && !is_file(__CHAMPS_DIR__."/.htaccess")){
//    copy(__CHAMPS_DIR__."/vendor/betocampoy/champs_framework/src/Help/initial_templates/.htaccess", __CHAMPS_DIR__."/.htaccess");
//}
//// Initial index.php file
//if(!file_exists(__CHAMPS_DIR__."/index.php") && !is_file(__CHAMPS_DIR__."/index.php")){
//    copy(__CHAMPS_DIR__."/vendor/betocampoy/champs_framework/src/Help/initial_templates/.htaccess", __CHAMPS_DIR__."/index.php");
//}

if(!defined("CHAMPS_STORAGE_ROOT_FOLDER")) define("CHAMPS_STORAGE_ROOT_FOLDER",  "storage");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER);

if(!defined("CHAMPS_STORAGE_TEMPORARY_FOLDER")) define("CHAMPS_STORAGE_TEMPORARY_FOLDER", "tmp");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_TEMPORARY_FOLDER);

if(!defined("CHAMPS_STORAGE_LOG_FOLDER")) define("CHAMPS_STORAGE_LOG_FOLDER", "log");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_LOG_FOLDER);

if(!defined("CHAMPS_STORAGE_UPLOAD_FOLDER")) define("CHAMPS_STORAGE_UPLOAD_FOLDER", "upload");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_UPLOAD_FOLDER);

if(!defined("CHAMPS_STORAGE_IMAGE_FOLDER")) define("CHAMPS_STORAGE_IMAGE_FOLDER", "images");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_IMAGE_FOLDER);

if(!defined("CHAMPS_STORAGE_FILE_FOLDER")) define("CHAMPS_STORAGE_FILE_FOLDER", "files");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_FILE_FOLDER);

if(!defined("CHAMPS_STORAGE_MEDIA_FOLDER")) define("CHAMPS_STORAGE_MEDIA_FOLDER", "medias");
full_folder_path(CHAMPS_STORAGE_ROOT_FOLDER."/".CHAMPS_STORAGE_MEDIA_FOLDER);


/*
 * Validate if mandatory CONSTANTS was defined and if possible, define with default value
 */

/* SYSTEM CONFIG */
if(!defined("CHAMPS_FRIENDLY_URL")) define("CHAMPS_FRIENDLY_URL", true);
// environment identifier
if(!defined("CHAMPS_ENVIRONMENT_IDENTIFIER") || !in_array(CHAMPS_ENVIRONMENT_IDENTIFIER, ["PRD", "DEV", "UAT"])){
    echo "The constant [CHAMPS_ENVIRONMENT_IDENTIFIER] must be defined as PRD for production or DEV for development or UAT for tests";
    die();
}

// url configs
if (!defined("CHAMPS_FORCE_HTTPS")) define("CHAMPS_FORCE_HTTPS", false);
if (CHAMPS_ENVIRONMENT_IDENTIFIER == "PRD" && (!defined("CHAMPS_URL_PRD") || empty(CHAMPS_URL_PRD))) {
    echo "The application is running in PRODUCTION environment, so um MUST define the constant [CHAMPS_URL_PRD] with the projects URL";
    die();
}
if (CHAMPS_ENVIRONMENT_IDENTIFIER == "DEV" && (!defined("CHAMPS_URL_DEV") || empty(CHAMPS_URL_DEV))) {
    echo "The application is running in DEVELOPMENT environment, so um MUST define the constant [CHAMPS_URL_DEV] with the projects URL";
    die();
}
if (CHAMPS_ENVIRONMENT_IDENTIFIER == "UAT" && (!defined("CHAMPS_URL_UAT") || empty(CHAMPS_URL_UAT))) {
    echo "The application is running in TEST environment, so um MUST define the constant [CHAMPS_URL_UAT] with the projects URL";
    die();
}

// DEFINING THE PROJECT URL
$projectUrl = strtolower(constant("CHAMPS_URL_" . CHAMPS_ENVIRONMENT_IDENTIFIER));
if (CHAMPS_FORCE_HTTPS) {
    $projectUrl = substr($projectUrl, 0, 8) == "https://"
        ? $projectUrl
        : (substr($projectUrl, 0, 7) == "http://" ? str_replace("http://", "https://", $projectUrl) : "https://{$projectUrl}");
}else{
    $projectUrl = (substr($projectUrl, 0, 8) == "https://" || substr($projectUrl, 0, 7) == "https://")
        ? $projectUrl
        : "http://{$projectUrl}";
}

define("CHAMPS_URL_PROJECT", $projectUrl[strlen($projectUrl) - 1] == "/" ? substr($projectUrl, 0, strlen($projectUrl) - 1) : $projectUrl);

// maintenance config
if(!defined("CHAMPS_SYS_UNDER_MAINTENANCE")) define("CHAMPS_SYS_UNDER_MAINTENANCE", false);
if(!defined("CHAMPS_SYS_MAINTENANCE_IP_EXCEPTIONS")) define("CHAMPS_SYS_MAINTENANCE_IP_EXCEPTIONS", []);
if(!defined("CHAMPS_SYS_MAINTENANCE_PAGE_TITLE")) define("CHAMPS_SYS_MAINTENANCE_PAGE_TITLE", "System Under Maintenance");
if(!defined("CHAMPS_SYS_MAINTENANCE_PAGE_TEXT")) define("CHAMPS_SYS_MAINTENANCE_PAGE_IMG", help_theme("/assets/images/developer.svg"));
if(!defined("CHAMPS_SYS_MAINTENANCE_PAGE_TEXT")) define("CHAMPS_SYS_MAINTENANCE_PAGE_TEXT", "Teste de texto");
if(!defined("CHAMPS_SYS_MAINTENANCE_ROUTE")) define("CHAMPS_SYS_MAINTENANCE_ROUTE", "/uhups/maintenance");
if(!defined("CHAMPS_SYS_FORBIDDEN_ROUTE")) define("CHAMPS_SYS_FORBIDDEN_ROUTE", "/uhups/forbidden");

/* Session name */
if(!defined("CHAMPS_SESSION_NAME") || empty(CHAMPS_SESSION_NAME)){
    echo "The constant [CHAMPS_SESSION_NAME] must be defined as string!";
    die();
}

// DATABASE CONNECTIONS
if(!defined("CHAMPS_DB_CONNECTIONS")){
    echo "The constant [CHAMPS_DB_CONNECTIONS] must be defined as an array to associate the environment and the database 
    with the connection constant name. Check ...documentation... for more information. 
    If your application won't connect to a DB, define this constant as a blank array";
    die();
}

// PASSWORD CONFIG
if(!defined("CHAMPS_PASSWD_ALGO")) define("CHAMPS_PASSWD_ALGO", PASSWORD_DEFAULT);
if(!defined("CHAMPS_PASSWD_OPTION")) define("CHAMPS_PASSWD_OPTION", ["cost" => 10]);
if(!defined("CHAMPS_PASSWD_MIN_LEN")) define("CHAMPS_PASSWD_MIN_LEN", 6);
if(!defined("CHAMPS_PASSWD_MAX_LEN")) define("CHAMPS_PASSWD_MAX_LEN", 50);
// DATE
if(!defined("CHAMPS_DATE_BR")) define("CHAMPS_DATE_BR", "d/m/Y");
if(!defined("CHAMPS_DATETIME_BR")) define("CHAMPS_DATETIME_BR", "d/m/Y H:i:s");
if(!defined("CHAMPS_DATE_APP")) define("CHAMPS_DATE_APP", "Y-m-d H:i:s");

// VIEW CONFIG
if(!defined("CHAMPS_VIEW_EXT")) define("CHAMPS_VIEW_EXT", "php");
if(!defined("CHAMPS_VIEW_WEB")) define("CHAMPS_VIEW_WEB", "web");
if(!defined("CHAMPS_VIEW_APP")) define("CHAMPS_VIEW_APP", "app");
if(!defined("CHAMPS_VIEW_OPR")) define("CHAMPS_VIEW_OPR", "opr");
if(!defined("CHAMPS_VIEW_ADM")) define("CHAMPS_VIEW_ADM", "admin");
if(!defined("CHAMPS_VIEW_MAIL")) define("CHAMPS_VIEW_MAIL", "email");

// MESSAGE CSS CLASSES
if(!defined("CHAMPS_MESSAGE_CLASS")) define('CHAMPS_MESSAGE_CLASS', 'message');
if(!defined("CHAMPS_MESSAGE_INFO")) define('CHAMPS_MESSAGE_INFO', 'info icon-info');
if(!defined("CHAMPS_MESSAGE_SUCCESS")) define('CHAMPS_MESSAGE_SUCCESS', 'success icon-check-square-o');
if(!defined("CHAMPS_MESSAGE_WARNING")) define('CHAMPS_MESSAGE_WARNING', 'warning icon-warning');
if(!defined("CHAMPS_MESSAGE_ERROR")) define('CHAMPS_MESSAGE_ERROR', 'error icon-warning');

// LOG
if(!defined("CHAMPS_LOG_RECYCLE")) define("CHAMPS_LOG_RECYCLE", 1);
if(!defined("CHAMPS_LOG_LEVEL")) define("CHAMPS_LOG_LEVEL", 'ERROR');
if(!defined("CHAMPS_LOG_FILE_NAME")) define("CHAMPS_LOG_FILE_NAME", 'application');
//ONLY FOR SLACK
if(!defined("CHAMPS_LOG_SLACK_ACTIVE")) define("CHAMPS_LOG_SLACK_ACTIVE", false);
if(!defined("CHAMPS_LOG_SLACK_WEBHOOK")) define("CHAMPS_LOG_SLACK_WEBHOOK", '');
if(!defined("CHAMPS_LOG_SLACK_CHANNEL")) define("CHAMPS_LOG_SLACK_CHANNEL", '');

// CROPPER IMAGES
if(!defined("CHAMPS_IMAGE_CACHE")) define("CHAMPS_IMAGE_CACHE", CHAMPS_STORAGE_ROOT_FOLDER . "/" . CHAMPS_STORAGE_IMAGE_FOLDER . "/cache");
if(!defined("CHAMPS_IMAGE_SIZE")) define("CHAMPS_IMAGE_SIZE", 2000);
if(!defined("CHAMPS_IMAGE_QUALITY")) define("CHAMPS_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

// AUTHENTICATION INFRASTRUCTURE
if(!defined("CHAMPS_AUTH_ROUTES_CREATE")) define('CHAMPS_AUTH_ROUTES_CREATE', true);
if(!defined("CHAMPS_OPTIN_ROUTES_CREATE")) define('CHAMPS_OPTIN_ROUTES_CREATE', true);
if(!defined("CHAMPS_AUTH_CLASS_HANDLER")) define('CHAMPS_AUTH_CLASS_HANDLER', \BetoCampoy\ChampsFramework\Controller\AuthController::class);
if(!defined("CHAMPS_AUTH_ENTITY")) define('CHAMPS_AUTH_ENTITY', 'auth_users');
//if(!defined("CHAMPS_AUTH_FIELD_KEY")) define('CHAMPS_AUTH_FIELD_KEY', "email");
if(!defined("CHAMPS_AUTH_REQUIRED_FIELDS")) define('CHAMPS_AUTH_REQUIRED_FIELDS', []);
if(!defined("CHAMPS_AUTH_MODEL")) define('CHAMPS_AUTH_MODEL', \BetoCampoy\ChampsFramework\Models\Auth\User::class); // optional, by default \BetoCampoy\ChampsFramework\Models\Auth\User::class will be used
if(!defined("CHAMPS_GLOBAL_PERMISSIONS")) define('CHAMPS_GLOBAL_PERMISSIONS', []); // optional - associative array to configure permissions where key = action and value = permission (ex. ['list' => 'listar']
if(!defined("CHAMPS_AUTH_ROUTES")){
    define("CHAMPS_AUTH_ROUTES", [
        "admin" => [
            "route" => "/admin",
            "handler" => null,
        ],
        "operator" => [
            "route" => "/operator",
            "handler" => null,
        ],
        "client" => [
            "route" => "/client",
            "handler" => null,
        ],
    ]);
}

// OAUTH2
// - FACEBOOK LOGIN
$facebookEnabled = false;
if(!defined("CHAMPS_OAUTH_FACEBOOK")) {
    define("CHAMPS_OAUTH_FACEBOOK", [
        "app_id" => "",
        "app_secret" => "",
        "app_callback" => "",
        "app_version" => "",
    ]);
}else{
    $authModel = CHAMPS_AUTH_MODEL;
    if (!empty(CHAMPS_OAUTH_FACEBOOK['app_id']) && !empty(CHAMPS_OAUTH_FACEBOOK['app_secret']) && !empty(CHAMPS_OAUTH_FACEBOOK['app_callback']) && !empty(CHAMPS_OAUTH_FACEBOOK['app_version'])){
        if(!class_exists("\League\OAuth2\Client\Provider\Facebook")){
            // gera um alerta
            (new \BetoCampoy\ChampsFramework\Log())->error("It was not possible to activate OAUTH2 Facebook login. Package \"league/oauth2-facebook\": \"^2.0\" wasn't installed");
        }elseif (!in_array('facebook_id', (new $authModel)->getColumns() ?? [])){
            (new \BetoCampoy\ChampsFramework\Log())->error("It was not possible to activate OAUTH2 Facebook login. There isn't the collumn facebook_id in database!");
        }
        else{
            $facebookEnabled = true;
        }
    }
}
define("CHAMPS_OAUTH_FACEBOOK_ENABLE", $facebookEnabled);

/* EMAIL */
if(!defined("CHAMPS_MAIL_OPTION_LANG")) define("CHAMPS_MAIL_OPTION_LANG","");
if(!defined("CHAMPS_MAIL_OPTION_HTML")) define("CHAMPS_MAIL_OPTION_HTML","");
if(!defined("CHAMPS_MAIL_OPTION_AUTH")) define("CHAMPS_MAIL_OPTION_AUTH","");
if(!defined("CHAMPS_MAIL_OPTION_SECURE")) define("CHAMPS_MAIL_OPTION_SECURE","");
if(!defined("CHAMPS_MAIL_OPTION_CHARSET")) define("CHAMPS_MAIL_OPTION_CHARSET","");
if(!defined("CHAMPS_MAIL_HOST")) define("CHAMPS_MAIL_HOST","");
if(!defined("CHAMPS_MAIL_PORT")) define("CHAMPS_MAIL_PORT","");
if(!defined("CHAMPS_MAIL_USER")) define("CHAMPS_MAIL_USER","");
if(!defined("CHAMPS_MAIL_PASS")) define("CHAMPS_MAIL_PASS","");
if(!defined("CHAMPS_MAIL_SENDER")) define("CHAMPS_MAIL_SENDER",[ "name" => "", "address" => ""]);

/* SEO */
if(!defined("CHAMPS_SITE_LANG")) define("CHAMPS_SITE_LANG", "pt-br");
if(!defined("CHAMPS_SITE_NAME")) define("CHAMPS_SITE_NAME", "Site Name");
if(!defined("CHAMPS_SITE_TITLE")) define("CHAMPS_SITE_TITLE", "Site Title!");
if(!defined("CHAMPS_SITE_DESCRIPTION")) define("CHAMPS_SITE_DESCRIPTION", "Site Description");
if(!defined("CHAMPS_SITE_DOMAIN")) define("CHAMPS_SITE_DOMAIN", "sitedomain.com.br");
if(!defined("CHAMPS_SITE_ADDR_STREET")) define("CHAMPS_SITE_ADDR_STREET", "Company Address");
if(!defined("CHAMPS_SITE_ADDR_NUMBER")) define("CHAMPS_SITE_ADDR_NUMBER", "Number");
if(!defined("CHAMPS_SITE_ADDR_COMPLEMENT")) define("CHAMPS_SITE_ADDR_COMPLEMENT", "Complement");
if(!defined("CHAMPS_SITE_ADDR_CITY")) define("CHAMPS_SITE_ADDR_CITY", "City");
if(!defined("CHAMPS_SITE_ADDR_STATE")) define("CHAMPS_SITE_ADDR_STATE", "State");
if(!defined("CHAMPS_SITE_ADDR_ZIPCODE")) define("CHAMPS_SITE_ADDR_ZIPCODE", "00000-000");
//SOCIAL CONFIG
if(!defined("CHAMPS_SOCIAL_TWITTER_CREATOR")) define("CHAMPS_SOCIAL_TWITTER_CREATOR", "");
if(!defined("CHAMPS_SOCIAL_TWITTER_PUBLISHER")) define("CHAMPS_SOCIAL_TWITTER_PUBLISHER", "");
if(!defined("CHAMPS_SOCIAL_FACEBOOK_PAGE")) define("CHAMPS_SOCIAL_FACEBOOK_PAGE", "");
if(!defined("CHAMPS_SOCIAL_FACEBOOK_AUTHOR")) define("CHAMPS_SOCIAL_FACEBOOK_AUTHOR", "");
if(!defined("CHAMPS_SOCIAL_GOOGLE_PAGE")) define("CHAMPS_SOCIAL_GOOGLE_PAGE", "Template");
if(!defined("CHAMPS_SOCIAL_GOOGLE_AUTHOR")) define("CHAMPS_SOCIAL_GOOGLE_AUTHOR", "Template");
if(!defined("CHAMPS_SOCIAL_FACEBOOK_APP")) define("CHAMPS_SOCIAL_FACEBOOK_APP", "");

// AUTHENTICATION INFRASTRUCTURE
if(!defined("CHAMPS_MINIFY_THEMES")) define('CHAMPS_MINIFY_THEMES', null);

if(CHAMPS_SYS_UNDER_MAINTENANCE && $_REQUEST['route'] != CHAMPS_SYS_MAINTENANCE_ROUTE){
    if($_SERVER['REMOTE_ADDR'] == '::1' &&  !in_array($_SERVER['REMOTE_ADDR'], CHAMPS_SYS_MAINTENANCE_IP_EXCEPTIONS)){
        redirect(url(CHAMPS_SYS_MAINTENANCE_ROUTE));
    }
}
