<?php

namespace BetoCampoy\ChampsFramework\ORM;

use BetoCampoy\ChampsFramework\Log;
use PDO;
use PDOException;

/**
 * Class Connect
 *
 * @package Source\Core\Model
 */
class Connect
{

    /** @var \PDO */
    private static ?PDO $instance = null;

    /** @var \PDOException|null */
    private static ?PDOException $error;

    /**
     * @param array|null $database
     *
     * @return \PDO|null
     */
    public static function getInstance(?array $database = null): ?PDO
    {
        $array_db = select_database_conn();

        if (empty(self::$instance) || $database != $array_db) {
            $db = $database ?? $array_db;

            // validate if array has needed information
            $dbFile = $db['dbfile'] ?? '';
            $dbDriver = $db["dbdriver"] ?? 'mysql';
            $dbHost = $db["dbhost"] ?? 'localhost';
            $dbName = $db["dbname"] ?? '';
            $dbPort = $db["dbport"] ?? '3306';
            $dbopt = [
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                \PDO::ATTR_CASE => \PDO::CASE_NATURAL
            ];

            if (strtolower($dbDriver) == 'sqlite') {
                $dsn = "sqlite:{$dbFile}";
            } else {
                $dsn = "{$dbDriver}:host={$dbHost};dbname={$dbName};port={$dbPort}";
            }

            try {
                self::$instance = new PDO(
                    $dsn,
                    ($db["dbuser"] ?? null),
                    ($db["dbpass"] ?? null),
                    $dbopt
                );
            } catch (PDOException $exception) {
                var_dump(["excep" => $exception]);die();
                self::$error = $exception;
            }
        }
        var_dump(["inst" => self::$instance]);die();
        return self::$instance;
    }

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