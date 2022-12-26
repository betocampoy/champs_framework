<?php

namespace BetoCampoy\ChampsFramework\ORM;

trait Mysql
{

    public function getColumnsMysql():array
    {
        try{
            $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table_name ";

            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }
            $stmt = $dbInstance->prepare($query);
            $stmt->execute(["table_name" => $this->entity]);

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