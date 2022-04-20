<?php

namespace BetoCampoy\ChampsFramework\ORM;

use PDO;
use PDOException;

/**
 * Class Connect
 *
 * @package Source\Core\Model
 */
class Connect
{
//    /** @const array */
//    private const OPTIONS = [
//        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
//        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
//        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
//        \PDO::MYSQL_ATTR_FOUND_ROWS => true,
//        \PDO::ATTR_CASE => \PDO::CASE_NATURAL
//    ];

    /** @var \PDO  */
    private static ?PDO $instance = null;

    /** @var \PDOException|null  */
    private static ?PDOException $error;

    /**
     * @param array|null $database
     *
     * @return \PDO|null
     */
    public static function getInstance(?array $database = null): ?PDO
    {
        if (empty(self::$instance) || $database != CHAMPS_DEFAULT_DB) {
            $db = $database ?? CHAMPS_DEFAULT_DB;

            if(strtolower($db['dbdriver']) == 'sqlite'){
                $dsn =  "sqlite:{$db['dbfile']}";
            }else{
                $dsn =  "{$db["dbdriver"]}:host={$db["dbhost"]};dbname={$db["dbname"]};port={$db["dbport"]}";
            }

            try {
                self::$instance = new PDO(
                  $dsn,
                  ($db["dbuser"] ?? null),
                  ($db["dbpass"] ?? null),
                  ($db["dboptions"] ?? null)
                );

            } catch (PDOException $exception) {
                var_dump($exception);
                self::$error = $exception;
            }
        }

        return self::$instance;
    }

//    /**
//     * @return \PDO|null
//     */
//    public static function getInstance(): ?PDO
//    {
//        if (empty(self::$instance))
//            try {
//
//                $ambiente = model_set_environment();
//                $db_driver = model_convert_str_to_constant('CHAMPS_DB_DRIVER_'.$ambiente);
//                $db_host = model_convert_str_to_constant('CHAMPS_DB_HOSTNAME_'.$ambiente);
//                $db_name = model_convert_str_to_constant('CHAMPS_DB_DATABASE_'.$ambiente);
//                $db_user = model_convert_str_to_constant('CHAMPS_DB_USERNAME_'.$ambiente) ?? null;
//                $db_pass = model_convert_str_to_constant('CHAMPS_DB_PASSWORD_'.$ambiente) ?? null;
//
//                if(strtolower($db_driver) == 'sqlite'){
//                    $db_dsn = "sqlite:{$db_name}";
//                }else{
//                    $db_dsn = "mysql:host={$db_host};dbname={$db_name}";
//                }
//
//                self::$instance = new \PDO(
//                    $db_dsn,
//                    $db_user,
//                    $db_pass,
//                    self::OPTIONS
//                );
//            } catch (\PDOException $exception) {
//                self::$error = $exception;
////                if(defined('CHAMPS_DB_CONNECTION_ERROR_PAGE')){
////                    $url = CHAMPS_DB_CONNECTION_ERROR_PAGE;
////                    if (filter_var($url, FILTER_VALIDATE_URL)) {
////                        header("Location: {$url}");
////                        exit;
////                    }
////
////                    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
////                        $location = error_url($url);
////                        header("Location: {$location}");
////                        exit;
////                    }
////                }
////                else{
////                    throw new \Exception("Vixe! Database Connection fail!");
////                }
//            }
//
//        return self::$instance;
//    }

    /**
     * @return \PDOException|null
     */
    public static function getError(): ?PDOException
    {
        return self::$error;
    }

    /**
     * Connect constructor.
     */
    private function __construct()
    {
    }

    /**
     * Connect clone.
     */
    private function __clone()
    {
    }

}