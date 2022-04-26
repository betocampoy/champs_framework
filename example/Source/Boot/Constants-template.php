<?php

/**
 * SESSION CONFIG
 */
define('CHAMPS_SESSION_NAME', "CHAMPS_FRAMEWORK");

/**
 * SYSTEM CONFIG
 */
define('CHAMPS_SYS_ENCODING', 'UTF-8');
define('CHAMPS_URL_TEST', 'http://www.localhost/projetos/repositorios/champs_framework/champs_framework/example/');
define('CHAMPS_URL', 'http://localhost/projetos/repositorios/champs_framework/champs_framework/example/');
define("CHAMPS_SYS_BOOT_FILES", [

]);
define("CHAMPS_DEFAULT_ROUTES", [
  "forbidden" => "ops/forbidden"
]);

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

/**
 * Authentication infrastructure
 */
// optional > default value [auth_users]
define('CHAMPS_AUTH_ENTITY', 'auth_users');
// optional
define('CHAMPS_AUTH_REQUIRED_FIELDS', []);

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
 * SEO CONFIG
 */
define("CHAMPS_SITE_NAME", "Framework Example Template");
define("CHAMPS_SITE_LANG", "pt-br");
define("CHAMPS_SOCIAL_TWITTER_CREATOR", "");
define("CHAMPS_SOCIAL_TWITTER_PUBLISHER", "");
define("CHAMPS_SITE_DOMAIN", "champsframework.com.br");
define("CHAMPS_SOCIAL_FACEBOOK_PAGE", "");
define("CHAMPS_SOCIAL_FACEBOOK_AUTHOR", "");
define("CHAMPS_SOCIAL_GOOGLE_PAGE", "Template");
define("CHAMPS_SOCIAL_GOOGLE_AUTHOR", "Template");
define("CHAMPS_SOCIAL_FACEBOOK_APP", "");

/**
 *
 */