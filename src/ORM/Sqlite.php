<?php

namespace BetoCampoy\ChampsFramework\ORM;


trait Sqlite
{

    public function getColumnsSqlite():array
    {
        try{
            $query = "SELECT sql FROM sqlite_master WHERE tbl_name = :table_name AND type = 'table'";

            $stmt = Connect::getInstance($this->database)->prepare($query);
            $stmt->execute(['table_name' => $this->entity]);

            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            if(!$row){
                return [];
            }
            $value = $row['sql'];
            $sanitColumns = explode(",", strstr($value, '('));

            $columns = [];
            foreach ($sanitColumns as $column){
                $columns[] = str_replace(["("], [""], strstr(trim($column), " ", true));

            }

            return array_filter($columns);
        }
        catch (\PDOException $exception){
            $this->fail = $exception;
            return [];
        }
    }

}