<?php

namespace BetoCampoy\ChampsFramework\ORM;

trait Mysql
{

    public function getColumnsMysql()
    {
        try{
            $query = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = :table_name ";

            $stmt = Connect::getInstance($this->database)->prepare($query);
            $stmt->execute(["table_name" => $this->entity]);

            $output = [];
            while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
                $output[] = $row['COLUMN_NAME'];
            }
            return $output;
        }
        catch (\PDOException $exception){
            $this->fail = $exception;
            return null;
        }
    }

}