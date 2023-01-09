<?php

namespace BetoCampoy\ChampsFramework\ORM;

trait Mysql
{

    public function getColumnsMysql():array
    {
        try{
            $dbName = isset($this->database['dbname'])
                ? $this->database
                : (isset(select_database_conn()['dbname']) ? select_database_conn()['dbname'] : '');
            $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :db_name AND table_name = :table_name ";
            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }

            $stmt = $dbInstance->prepare($query);
            $stmt->execute(["db_name" => $dbName, "table_name" => $this->entity]);

            $output = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $output[] = $row['COLUMN_NAME'];
            }
            return $output;
        }
        catch (\PDOException $exception){
            $this->fail = $exception;
            return [];
        }
    }

}