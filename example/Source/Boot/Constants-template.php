<?php
/**
 * IDENTIFYING IF RUNNING ENVIRONMENT IS PRODUCTION
 *
 * This global constant is important to inform to framework if the app is running in production
 */
define('CHAMPS_RUNNING_IN_PRODUCTION', false);

/**
 * SESSION CONFIG
 */
define('CHAMPS_SESSION_NAME', "CHAMPS_FRAMEWORK");

/**
 * SYSTEM CONFIG
 */
define('CHAMPS_SYS_ENCODING', 'UTF-8');
define('CHAMPS_FRIENDLY_URL', true);
define('CHAMPS_URL_TEST', 'http://www.localhost/projetos/repositorios/champs_framework/champs_framework/example/');
define('CHAMPS_URL', 'http://localhost/projetos/repositorios/champs_framework/champs_framework/example/');
define("CHAMPS_SYS_BOOT_FILES", []);
define("CHAMPS_DEFAULT_ROUTES", [
  "forbidden" => "ops/forbidden"
]);
define("CHAMPS_AUTH_ROUTES", [
  "admin" => [
    "route" => "/adm",
    "handler" => "Login:method",
  ],
  "operator" => [
    "route" => "/opr",
    "handler" => "Login:method",
  ],
  "client" => [
    "route" => "/cli",
    "handler" => "Login:method",
  ],
]);
// DATE FORMATS
define("CHAMPS_DATE_BR", "d/m/Y");
define("CHAMPS_DATETIME_BR", "d/m/Y H:i:s");
define("CHAMPS_DATE_APP", "Y-m-d H:i:s");

/**
 * DATABASE CONFIG
 */
define('CHAMPS_DEFAULT_DB', [
    // for sqlite
    //  "dbdriver" => 'sqlite',
    //  "dbfile" => __DIR__ . '/sqlite_db_example.db',

    // for mysql
    "dbdriver" => 'mysql',
    "dbhost" => "localhost",
    "dbport" => "3306",
    "dbname" => "jadsoft",
    "dbuser" => "dbuser",
    "dbpass" => "dbaccess",
    "dboptions" =>
      [
        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
      ]
]);
define('CHAMPS_DB_PREFIX', "app_");

/**
 * AUTHENTICATION INFRASTRUCTURE
 */
// optional > default value [auth_users]
define('CHAMPS_AUTH_ENTITY', 'auth_users');
// optional
define('CHAMPS_AUTH_REQUIRED_FIELDS', []);
define('CHAMPS_AUTH_MODEL', 'class full namespace'); // optional, by default \BetoCampoy\ChampsFramework\Models\Auth\User::class will be used
define('CHAMPS_GLOBAL_PERMISSIONS', []); // optional - associative array to configure permissions where key = action and value = permission (ex. ['list' => 'listar']

// LOGIN SOCIAL
define("CHAMPS_OAUTH_FACEBOOK", [
  "app_id" => "",
  "app_secret" => "",
  "app_redirect" => "",
  "app_version" => "",
]);

/**
 * PASSWORD CONFIG
 */
define("CHAMPS_PASSWD_ALGO", PASSWORD_DEFAULT);
define("CHAMPS_PASSWD_OPTION", ["cost" => 10]);
define("CHAMPS_PASSWD_MIN_LEN", 6);
define("CHAMPS_PASSWD_MAX_LEN", 50);

/**
 * LOG CONFIG
 */
//GENERAL
define("CHAMPS_LOG_FILES_DIR", "storage/logs");
define("CHAMPS_LOG_FILES_NAME", "database.log");
define("CHAMPS_LOG_RECYCLE", 1);
define("CHAMPS_LOG_LEVEL", 'ERROR');
//ONLY FOR SLACK
define("CHAMPS_LOG_SLACK_ACTIVE", false);
define("CHAMPS_LOG_SLACK_WEBHOOK", "");
define("CHAMPS_LOG_SLACK_CHANNEL", "");

/**
 * VIEW CONFIG
 */
define("CHAMPS_VIEW_PATH", __DIR__);
define("CHAMPS_VIEW_EXT", "php");
define("CHAMPS_VIEW_WEB", "web");
define("CHAMPS_VIEW_APP", "app");
define("CHAMPS_VIEW_OPR", "app");
define("CHAMPS_VIEW_ADM", "admin");
define("CHAMPS_VIEW_MAIL", "email");
// MESSAGE CSS CLASSES
define('CHAMPS_MESSAGE_CLASS', 'message');
define('CHAMPS_MESSAGE_INFO', 'info icon-info');
define('CHAMPS_MESSAGE_SUCCESS', 'success icon-check-square-o');
define('CHAMPS_MESSAGE_WARNING', 'warning icon-warning');
define('CHAMPS_MESSAGE_ERROR', 'error icon-warning');

/**
 * NAVIGATION
 */
define('CHAMPS_NAVBAR_STYLE', [
  CHAMPS_VIEW_WEB => 'bootstrap',
  CHAMPS_VIEW_ADM => 'bootstrap4',
]);

/**
 * EMAIL CONFIG
 */
define("CHAMPS_MAIL_OPTION_LANG","");
define("CHAMPS_MAIL_OPTION_HTML","");
define("CHAMPS_MAIL_OPTION_AUTH","");
define("CHAMPS_MAIL_OPTION_SECURE","");
define("CHAMPS_MAIL_OPTION_CHARSET","");
define("CHAMPS_MAIL_HOST","");
define("CHAMPS_MAIL_PORT","");
define("CHAMPS_MAIL_USER","");
define("CHAMPS_MAIL_PASS","");
define("CHAMPS_MAIL_SENDER",[
  "name" => "",
  "address" => "",
]);

/**
 * SITE CONFIG
 */
define("CHAMPS_SITE_NAME", "Framework Example Template");
define("CHAMPS_SITE_TITLE", "Framework irá ajuda-lo a desenvolver mais rápido!");
define("CHAMPS_SITE_DESC", "Descreisadasdsa dsad sa dsa.");
define("CHAMPS_SITE_LANG", "pt-br");
define("CHAMPS_SITE_DOMAIN", "champsframework.com.br");
define("CHAMPS_SITE_ADDR_STREET", "Rua Capitão Simões");
define("CHAMPS_SITE_ADDR_NUMBER", "111");
define("CHAMPS_SITE_ADDR_COMPLEMENT", "Centro");
define("CHAMPS_SITE_ADDR_CITY", "Ibitinga");
define("CHAMPS_SITE_ADDR_STATE", "SP");
define("CHAMPS_SITE_ADDR_ZIPCODE", "00000-00");

/**
 * SOCIAL CONFIG
 */
define("CHAMPS_SOCIAL_TWITTER_CREATOR", "");
define("CHAMPS_SOCIAL_TWITTER_PUBLISHER", "");
define("CHAMPS_SOCIAL_FACEBOOK_PAGE", "");
define("CHAMPS_SOCIAL_FACEBOOK_AUTHOR", "");
define("CHAMPS_SOCIAL_GOOGLE_PAGE", "Template");
define("CHAMPS_SOCIAL_GOOGLE_AUTHOR", "Template");
define("CHAMPS_SOCIAL_FACEBOOK_APP", "");

/**
 * UPLOAD
 */
define("CHAMPS_STORAGE_ROOT_FOLDER", "storage");
define("CHAMPS_STORAGE_TEMPORARY_FOLDER", "tmp");
define("CHAMPS_STORAGE_LOG_FOLDER", "log");
define("CHAMPS_STORAGE_UPLOAD_FOLDER", "upload");
define("CHAMPS_STORAGE_IMAGE_FOLDER", "images");
define("CHAMPS_STORAGE_FILE_FOLDER", "files");
define("CHAMPS_STORAGE_MEDIA_FOLDER", "medias");

/**
 * IMAGES
 */
define("CHAMPS_IMAGE_CACHE", CHAMPS_STORAGE_ROOT_FOLDER . "/" . CHAMPS_STORAGE_IMAGE_FOLDER . "/cache");
define("CHAMPS_IMAGE_SIZE", 2000);
define("CHAMPS_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

/**
 * MINIFY
 */
define("CHAMPS_MINIFY_THEMES", [
    // always, dev
    "minify" => "always",
    "Example" => [
      "css" => [
        "/shared/styles/boot.css",
        "/shared/styles/normalize.css",
      ] ,
      "js" => [
        "js.js"
      ],
      "jquery-engine" => true,
    ]
]);