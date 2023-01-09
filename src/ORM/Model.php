<?php

namespace BetoCampoy\ChampsFramework\ORM;


use BetoCampoy\ChampsFramework\Log;
use BetoCampoy\ChampsFramework\Message;
use function ICanBoogie\pluralize;

abstract class Model
{
    use SoftDelete, Relationships, Mysql, Sqlite;

    /**
     * Database driver.
     * the acceptable values are: mysql, sqlite
     *
     * @var null|string
     */
    protected ?string $dbDriver = null;

    /**
     * Array with the connection database information. If null, the model will looking for connection information
     * at the global constant CHAMPS_DEFAULT_DB
     *
     * example:
     *
     * [
     *      "dbdriver" => 'mysql',
     *      // "dbfile" => __DIR__ . '/sqlite_db_example.db', //only for sqlite
     *      "dbhost" => "localhost",
     *      "dbport" => "3306",
     *      "dbname" => "jadsoft",
     *      "dbuser" => "dbuser",
     *      "dbpass" => "dbaccess",
     *      "dboptions" =>
     *          [
     *              \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
     *              \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
     *              \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
     *              \PDO::ATTR_CASE => \PDO::CASE_NATURAL
     *          ]
     * ]
     *
     * @var null|array
     */
    protected ?string $database = null;

    /**
     * Database table name
     *
     * @var string|null
     */
    protected ?string $entity = null;

    /**
     * Array with protected database field names
     * These fields can't be changed
     *
     * @var array $protected
     */
    protected array $protected = ['id'];

    /**
     * Array with required fields
     * If there isn't any of these fields, create or update will fail
     *
     * @var array $required database table
     */
    protected array $required;

    /** @var array specify fields that can be null */
    protected array $nullable = [];

    /**
     * Stored the original information after update.
     * So it is possible to check if some fields was changed
     *
     * @var object|null
     */
    protected ?object $oldData;

    /**
     * Stored the model data
     *
     * @var object|null
     */
    protected ?object $data;

    /** @var \PDOException|null */
    protected ?\PDOException $fail = null;

    /** @var array|null */
    protected ?array $messages = null;

    /** @var Log  */
    protected Log $log;

    /** @var string */
    protected string $query;

    /** @var array|null */
    protected ?array $params = [];

    /** @var string|null */
    protected ?string $terms = null;

    /** @var string|null */
    protected ?string $order = null;

    /** @var string|null */
    protected ?string $group = null;

    /** @var string */
    protected string $columns = 'm.*';

    /** @var string|null */
    protected ?string $join = null;

    /** @var string|null */
    protected ?string $limit = null;

    /** @var string|null */
    protected ?string $offset = null;

    /** @var array $aliasToEntities */
    protected array $aliasToEntities;

    /**
     * Optionaly define this attribute in children classes to customise the behavior of [fill] method.
     *
     * EXAMPLE :
     *           protected $fillable = [
     *               'datatable_field_name1' => FILTER_SANITIZE_NUMBER_INT,
     *               'datatable_field_name2' => FILTER_SANITIZE_STRING,
     *          ];
     *
     * @var array $fillable
     */
    protected array $fillable = [];

    /** @var array $controlColumns */
    protected array $controlColumns = ['created_at', "updated_at"];

    /**
     * Model constructor.
     */
    public function __construct()
    {
        $this->entityName();
        $this->dbDriver =  isset($this->database['dbdriver']) ? ucfirst($this->database['dbdriver']) : "Mysql";
        $this->protected = array_merge($this->protected, $this->controlColumns);
//        $this->required = $required;
        $this->aliasToEntities = [];
        if(in_array($this->softDeleteColumnName, $this->getColumns())){
            $this->whereSoftDelete = "{$this->softDeleteColumnName} IS NULL";
            $this->softDelete = $this->softDeleteForcedDisable ? false : true;
        }
        $this->log = new Log(__CLASS__);
    }

    /**
     * This method is used to return the filter type that should be used in filter_var functions for example.
     * The definition of what filter each field has in in the option attribute $this->fillable. If the field
     * there isn't in the array fillable, it will be used by defauld the [FILTER_SANITIZE_STRIPPED]
     *
     * @param string $field
     *
     * @return int
     */
    public function getFilterDataType(string $field):int
    {
        $field = strtolower($field);
        return isset($this->fillable[$field]) && in_array($field, $this->fillable[$field]) ? $this->fillable[$field] : FILTER_SANITIZE_STRIPPED;
    }

    /**
     * This method will fullfill the object data attribute using the array $data information.
     * for security purposes, the data will be filtered by defined filter in fillable attribute or FILTER_SANITIZE_STRIPPED
     *
     * @param array $data
     *
     * @return $this
     */
    public function fill(array $data = []): Model
    {
        $columns = $this->getColumns();
        foreach ($data as $key => $value) {
            if (in_array($key, $columns)) {
                $filteredValue = isset($this->fillable[$key]) && $this->fillable[$key] == "escape"
                    ? $value : filter_var($value, $this->fillable[$key] ?? FILTER_SANITIZE_STRIPPED);

                if ($filteredValue !== '' || in_array($key, $this->nullable)) {
                    $this->$key = empty($filteredValue) ? null : $filteredValue;
                }
            }
        }
        return $this;
    }

    /**
     * Returns an array with the name of all table's columns
     *
     * @return array|null
     */
    public function getColumns()
    {
        return call_user_func(array($this, "getColumns{$this->dbDriver}"));
    }

    /**
     * Check if the column exists in the model entity
     *
     * @param string $column_name
     * @return bool
     */
    public function columnExists(string $column_name):bool
    {
        return in_array($column_name, $this->getColumns() ?? []);
    }

    /**
     * Check if the model entity exists in database
     *
     * @return bool
     */
    public function entityExists():bool
    {
        return $this->count() !== null;
    }

    /**
     * This magic method will create a stdClass object named data to store all data value.
     * If you want to manipulate the data before save, create a method with the name of the field with 'prepare' prefix
     * in camel case.
     * Example: to validate the field [name] create the method prepareName($value) in child class
     * this methods must return the manipulated value;
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $funcName = "prepare".str_studly_case($name);
        if(method_exists($this, $funcName)){
            $value = $this->$funcName($value);
        }

        if (empty($this->oldData)) {
            $this->oldData = new \stdClass();
        }

        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        if(xdebug_call_class() == 'PDOStatement'){
            $this->oldData->$name = $this->oldData->$name ?? $value;
        }
        $this->data->$name = $value;
    }

    /**
     * Returns if the field is set in $this->data attribute
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    /**
     * Returns the data in $this->data attribute. If it is necessary create a specific getter
     *
     * @param $name
     * @return null
     */
    public function __get($name)
    {
        $funcName = "formatGet".str_studly_case($name);

        if(empty($this->data))
            $this->data = new \stdClass();

        if(empty($this->oldData))
            $this->oldData = new \stdClass();

        if(method_exists($this, $funcName)){
            return $this->$funcName($this->data->$name);
        }
        return (property_exists($this->data, $name) && $this->data->$name ? $this->data->$name : null);
    }

    /**
     * Returns a stdClass with all stored data in the object
     *
     * @return null|object
     */
    public function data()
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }
        return $this->data;
    }

    /**
     * With this method, preserve the original data before any change, so if it's necessary you can rollback calling the method rollbackOldData
     *
     * @return object|\stdClass|null
     */
    public function oldData()
    {
        if (empty($this->oldData)) {
            $this->oldData = new \stdClass();
        }
        return $this->oldData;
    }

    /**
     * Validated if data, or some specifics fields was changed
     *
     * - Call the method without params and all data will be checked
     * - Inform the $arrayOfFields params, and only specified fields will be checked
     * - Pass the $unsetArrayOfFieldsFromData as true, and the specified fields will be unset from data
     * and remain fields will be checked
     *
     * @param array $arrayOfFields
     * @param bool  $unsetArrayOfFieldsFromData
     *
     * @return bool
     */
    public function dataChanged(array $arrayOfFields = [], bool $unsetArrayOfFieldsFromData = false):bool
    {
        if (empty($arrayOfFields)){
            return $this->data() != $this->oldData();
        }

        $data = $unsetArrayOfFieldsFromData ? $this->data() : [];
        $oldData = $unsetArrayOfFieldsFromData ? $this->oldData() : [];

        foreach ($arrayOfFields as $field){

            if($unsetArrayOfFieldsFromData){

                $data->$field = isset($data->$field) ? $data->$field : null;
                $oldData->$field = isset($oldData->$field) ? $oldData->$field : null;

                unset($data->$field);
                unset($oldData->$field);
            }else{

                $this->data->$field = isset($this->data->$field) ? $this->data->$field : null;
                $this->oldData->$field = isset($this->oldData->$field) ? $this->oldData->$field : null;

                $data[] = $this->data->$field;
                $oldData[] = $this->oldData->$field;
            }

        }

        return $data != $oldData;
    }

    public function rollbackOldData(): bool
    {

        if (!empty($this->oldData)) {
            foreach ($this->oldData as $field => $value){
                $this->$field = $value;
            }
            return $this->save();
        }
        return true;
    }

    /**
     * Getter to access the fail PDO exception message
     *
     * @return \PDOException
     */
    public function fail()
    {
        return $this->fail;
    }

    /**
     * @return \BetoCampoy\ChampsFramework\Message|null
     */
    public function message():?Message
    {
        $message = new Message();
        foreach ($this->messages as $type => $msg){
            if($msg){
                if(method_exists($message, $type)){
                    $message->$type($msg);
                }
            }
        }
        return $message ?? null;
    }

    /**
     * @param string $type
     * @param string $message
     */
    protected function setMessage(string $type, string $message):void
    {
        $this->messages[$type][] = $message;
    }

    /*****************************************
     * METODOS UTILIZADOS PELO QUERY BUILDER
     *****************************************/

    /**
     * Query builder [find] method.
     * This method build the query to access information at database.
     * Optionally its possible  informing the $terms, $params and the desired columns.
     *
     * There is others build queries methods, some must be used before find and others after
     * - methods used before: where
     * - methods used after:
     *
     * @param string|null $terms
     * @param string|null $params
     * @param string|null $columns
     *
     * @return $this
     */
    public function find(?string $terms = null, ?string $params = null, ?string $columns = null)
    {
        if($terms){
            $this->where($terms, $params);
        }
        if($columns){
            $this->columns($columns);
        }

        //        if($terms){
        //            $this->terms = $this->terms ? " {$this->terms} AND {$terms} " : " WHERE {$terms} ";
        //        }

        //        if($params){
        //            $params = $this->parseParams($params);
        //
        //            foreach ($params as $key => $value){
        //                $this->params[$key] = $value;
        //            }
        //        }

        return $this;
    }

    /**
     * Find a record by Id in database and return a Model object or null
     *
     * @param int $id
     * @param string $columns
     * @return null|mixed|Model
     */
    public function findById(int $id, string $columns = "m.*")
    {
        return $this->where("m.id = :id", "id={$id}")->columns($columns)->fetch();
    }

    /**
     * Query builder [where] method.
     * Add new terms and params to the query. Must be used before find.
     * This method helps to create data scopes. To create a new scope, create a new method
     * to the model. See a example of a scope below
     *
     * public function active() : Model
     * {
     *
     * $this->where("active=:active", "active=true");
     *
     * return $this;
     * }
     *
     * So, instead of passing the active terms and params every time, you call the scoped method
     *
     * Ex: $scopedData = $model->active()->find()->fetch(true)
     *
     * @param        $terms
     * @param null   $params
     * @param string $operator
     *
     * @return $this
     */
    public function where($terms = null, $params = null,  $operator = "AND") : Model
    {
        $this->terms($terms, $operator);
        $this->params($params);
        return $this;
    }

    /**
     * @param null $field
     * @param array $paramValues
     * @param string $operator
     * @return $this
     */
    public function whereIn($field = null, $paramValues = [], $operator = "AND"): Model
    {
        if (!$field) {
            return $this;
        }

        if (count($paramValues) == 0) {
            return $this;
        }

        $termsIn = "";
        $params = "";
        $fieldName = (strstr($field, '.') ? str_replace('.', '', strstr($field, '.')) : $field);
        foreach ($paramValues as $key => $paramValue) {
            $termsIn .= $termsIn ? ", :in_{$fieldName}_{$key}" : ":in_{$fieldName}_{$key}";
            $params .= $params ? "&in_{$fieldName}_{$key}={$paramValue}" : "in_{$fieldName}_{$key}={$paramValue}";
        }
        $terms = "{$field} IN ({$termsIn})";
        $this->terms($terms, $operator);
        $this->params($params);

        return $this;
    }

    /**
     * Query builder [columns] method.
     *
     * @param string $columns
     *
     * @return $this
     */
    public function columns(string $columns):Model
    {
        $sanit_columns = '';
        foreach (explode(',', $columns) as $column){
            $sanit_column = strpos($column, '.') ? trim($column) : "m.".trim($column);
            $sanit_columns = $sanit_columns ? "{$sanit_columns}, {$sanit_column}" : "{$sanit_column}";
        }
        $this->columns = $sanit_columns;
        return $this;
    }

    /**
     * Query builder [order] method.
     * Add the columns to sort data
     *
     * @param string $columnOrder
     * @return Model
     */
    public function order(string $columns):Model
    {
        $sanit_columns = '';
        foreach (explode(',', $columns) as $column){
            $sanit_column = strpos($column, '.') ? trim($column) : "m.".trim($column);
            $sanit_columns = $sanit_columns ? "{$sanit_columns}, {$sanit_column}" : "{$sanit_column}";
        }
        $this->order = " ORDER BY {$sanit_columns} ";
        return $this;
    }

    /**
     * Query builder [order] method.
     * Add the columns to sort data
     *
     * @param string $columnOrder
     * @return Model
     */
    public function group(string $columns):Model
    {
        $sanit_columns = '';
        foreach (explode(',', $columns) as $column){
            $sanit_column = strpos($column, '.') ? trim($column) : "m.".trim($column);
            $sanit_columns = $sanit_columns ? "{$sanit_columns}, {$sanit_column}" : "{$sanit_column}";
        }
        $this->group = " GROUP BY {$sanit_columns} ";
        return $this;
    }

    /**
     * Query builder [limit] method.
     * Limit amount of registers in offset
     *
     * @param int $limit
     * @return Model
     */
    public function limit($limit):Model
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * Query builder [offset] method.
     * Select the offset of dataset
     *
     * @param int $offset
     * @return Model
     */
    public function offset( $offset):Model
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }

    /**
     * Query builder [join] method.
     * Used to join a different model to que query. The relation of the joined model must be with the main model.
     *
     * if you need to join a model with another joined model, you must use the method addModelToJoin
     *
     * @param string $joinModel
     * @param string $terms
     * @param string|null $columns
     *
     * @return $this
     */
    public function join(string $joinModel, string $terms, string $params = null, string $joinType = 'LEFT', string $modelAlias = 'j')
    {
        $joinType = (in_array( strtoupper($joinType), ['INNER', 'LEFT'])) ? $joinType : 'LEFT';
        $model = new $joinModel;
        $this->addAliasToEntity($modelAlias, $model->entity);
        $this->params($params);
        $this->join .= " {$joinType} JOIN ". $model->entity . " ON ($terms) ";
        return $this;
    }

    /**
     * Query builder [addModelToJoin]d method.
     * Used to join a different model to que query.
     *
     * @param        $addedModel
     * @param        $addedAlias
     * @param        $relatedModel
     * @param        $relatedAlias
     * @param        $terms
     * @param null   $params
     * @param string $joinType
     *
     * @return $this
     */
    public function addModelToJoin($addedModel, $addedAlias, $relatedModel, $relatedAlias, $terms, $params = null, $joinType = 'LEFT')
    {
        $joinType = (in_array( strtoupper($joinType), ['INNER', 'LEFT'])) ? $joinType : 'LEFT';
        $addedModel = new $addedModel;
        $relatedModel = new $relatedModel;
        $this->addAliasToEntity($addedAlias, $addedModel->entity);
        $this->addAliasToEntity($relatedAlias, $relatedModel->entity);
        $this->params($params);
        $this->join .= " {$joinType} JOIN ". $addedModel->entity . " ON ($terms) ";
        return $this;
    }

    /**
     * Query builder [fetch] is responsible to execute the query at the database and return de recordset.
     *
     * @param bool $all
     *
     * @return array|mixed|null|Model
     */
    public function fetch( $all = false)
    {
        try {
            $count = clone $this;
            if(!$count->count() > 0){
                return [];
            }

            $this->addAliasToEntity("m", $this->entity);
            $this->query = "SELECT {$this->columns} FROM " . $this->entity . " ";
            if($this->whereSoftDelete){
                $this->where($this->whereSoftDelete);
            }
            $query = $this->queryTransformFromTo($this->query . $this->join . $this->terms . $this->group . $this->order . $this->limit . $this->offset);
            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }
            $stmt = $dbInstance->prepare($query);
            $stmt->execute($this->params);

            if ($all) {
//                $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
                $rst =  $stmt->fetchAll(\PDO::FETCH_CLASS, static::class);
                return $rst;
            }
//            $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
//            $rst = $stmt->fetch();
            $rst = $stmt->fetchObject(static::class);
            return $rst;

        } catch (\PDOException $exception) {
            $this->fail = $exception;
            $this->log->critical(__METHOD__, $exception->getTrace());
            $this->setMessage('error', "Ocorreu uma falha");
            return null;
        }
    }

    /**
     * Query builder [count] method.
     * Used to count records in recordset. Must be used after method [find].
     *
     * @param string $key
     * @return int
     */
    public function count( $key = "m.id")
    {
        try{
            $this->addAliasToEntity("m", $this->entity);

//            $this->query = "SELECT COUNT(id) FROM " . $this->entity . " ";

            // verify if the softDelete is active and define de DataSet
            //            if($this->softDelete && $this->whereSoftDelete){
            if($this->whereSoftDelete){
                $this->where($this->whereSoftDelete);
            }

//            $query = $this->queryTransformFromTo("SELECT {$this->entity}.{$key} ".strstr($this->query, " FROM ") . $this->join . $this->terms);
            $query = $this->queryTransformFromTo("SELECT COUNT(m.id) FROM " . $this->entity . " " . ($this->join ?? " ") . ($this->terms ?? " ") );
            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }
            $stmt = $dbInstance->prepare($query);
            $stmt->execute($this->params);
            return $stmt->fetchColumn() ?? 0;
        } catch (\PDOException $exception){
            $this->fail = $exception;
            $this->log->critical(__METHOD__, $exception->getTrace());
            $this->setMessage('error', "Ocorreu uma falha");
            return null;
        }

    }

    /**
     * This method is used to create a new record in database. It must be called by public method [save]
     *
     * @param array $data
     * @return int|null
     */
    protected function create(array $data)
    {
        try {
            if(user() && in_array('created_by', $this->getColumns())){
                $data['created_by'] = user()->id;
            }

            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $query = "INSERT INTO " . $this->entity . " ({$columns}) VALUES ({$values})";

            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }
            $dbInstance->setAttribute(\PDO::ATTR_EMULATE_PREPARES,TRUE);
            $stmt = $dbInstance->prepare($query);

//            $db = Connect::getInstance($this->database);
//            $db->setAttribute(\PDO::ATTR_EMULATE_PREPARES,TRUE);
//            $stmt = $db->prepare("INSERT INTO " . $this->entity . " ({$columns}) VALUES ({$values})");

            $stmt->execute($this->filter($data));

            return $dbInstance->lastInsertId();
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            $this->log->critical(__METHOD__, $exception->getTrace());
            $this->setMessage('error', "Ocorreu uma falha");
            return null;
        }
    }

    /**
     * This method is used to update a record in database. It must be called by public method [save]
     *
    //     * @param array $data
    //     * @param string $terms
    //     * @param string $params
    //     * @return int|null
     */
    public function update(array $data, string $terms = null, string $params = null)
    {
        $this->addAliasToEntity("m", $this->entity);
        $this->where($terms, $params);

        try {
            if(user() && in_array('updated_by', $this->getColumns())){
                $data['updated_by'] = user()->id;
            }
            $dateSet = [];
            foreach ($data as $bind => $value) {
//                $bindParam = strpos($bind, '.') ? str_replace(".", "updt_", strstr($bind, '.')) : "updt_{$bind}";
//                $dataBind = [$bindParam => $value];
                $dateSet[] = "{$bind} = :{$bind}";
//                $dateSet[] = "{$bind} = :{$bindParam}";
            }
            $dateSet = implode(", ", $dateSet);

            $query = $this->queryTransformFromTo("UPDATE " . $this->entity . " SET {$dateSet} $this->terms");
//            $stmt = Connect::getInstance($this->database)->prepare($query);
//            $stmt->execute($this->filter(($this->params ? array_merge($data, $this->params) : $data)));
//            return ($stmt->rowCount() ? $stmt->rowCount() : 1);

            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }
            $stmt = $dbInstance->prepare($query);
            $stmt->execute($this->filter(($this->params ? array_merge($data, $this->params) : $data)));
            return ($stmt->rowCount() ? $stmt->rowCount() : 1);
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            $this->log->critical(__METHOD__, $exception->getTrace());
            $this->setMessage('error', "Ocorreu uma falha");
            return null;
        }
    }

    /**
     * This method is used to create or update a record in database. If you need custom validations
     * before create or update, rewrite this method in the child class calling the parent method at
     * the end.
     *
     * @param bool $activeRelationalIntegrity
     *
     * @return bool
     */
    public function save(bool $activeRelationalIntegrity = true):bool
    {

        if (!$this->required()) {
            $this->setMessage('error', "Preencha todos os campos para continuar.");
            return false;
        }

        /** Update */
        if (!empty($this->id)) {
            $id = $this->id;

            if($this->dataChanged() && !$this->beforeUpdate()) {
                return false;
            }

            if ($activeRelationalIntegrity && !$this -> beforeUpdate()) {
                if (empty($this -> messages['error'])) {
                    $this -> messages['error'][]
                        = "Operação não executada para garantir a integridade dos dados.";
                }
                return false;
            }

//            $this->customSafeOnUpdate();
            if(!$this->update($this->safeOnUpdate(), "id = :id", "id={$id}")){
                return false;
            }
            if ($this->fail()) {
                $this->setMessage('error', "Erro ao atualizar, verifique os dados.");
                return false;
            }

            if($this->dataChanged()){
                $this -> afterUpdate();
            }

        }

        /** Create */
        if (empty($this->id)) {

            if ($activeRelationalIntegrity && !$this -> beforeCreate() ) {
                if (empty($this -> messages['error'])) {
                    $this -> messages['error'][]
                      = "Operação não executada para garantir a integridade dos dados.";
                }
                return false;
            }

            $id = $this->create($this->safeOnCreate());

            if (isset($this->messages['error'])) {
                return false;
            }

            if ($this->fail()) {
                $this->setMessage('error', "Erro ao cadastrar, verifique os dados.");
                return false;
            }

            if(!$id){
                $this->setMessage('error', "Erro ao cadastrar, verifique os dados.");
                return false;
            }

            $class_name = get_class($this);
            $this->data = (new $class_name)->findById($id)->data();
            $this->afterCreate();

        }

        return true;
    }

    /**
     * @return int
     */
    public function lastId()
    {
        //        return clsConexao::getInstance()->query("SELECT MAX(id) as maxId FROM {$this->entity}")->fetch()->maxId + 1;
        return Connect::getInstance($this->database)->query("SELECT MAX(id) as maxId FROM {$this->entity}")->fetch()->maxId + 1;
    }

    /**
     * This method is used to physical delete records in database.
     * It's possible use this method chained with [where] method and scopes
     *
     * @param null|string $terms
     * @param null|string $params
     * @return bool
     */
    public function forceDelete(string $terms = null, string $params = null):bool
    {
        $this->addAliasToEntity("m", $this->entity);
        
        if($terms){
            $this->where($terms, $params);
        }

        if(empty($this->terms)){
            $this->setMessage('error', "Operação de DELETE inválida no banco de dados.");
            return false;
        }

        if(!$this->beforeDelete()){
            if(!isset($this->messages['error'])){
                $this->setMessage('error', "Operação não executada para garantir a integridade dos dados.");
            }
            return false;
        }

        try {

            if($this->join){
                $modelsToDelete = $this->entity;
                if(array_key_exists("through", $this->aliasToEntities)){
                    $modelsToDelete .= ", {$this->aliasToEntities['through']}";
                }
                $query = $this->queryTransformFromTo("DELETE {$modelsToDelete} FROM {$this->entity} $this->join $this->terms");
            }else{
                $query = $this->queryTransformFromTo("DELETE FROM {$this->entity} $this->terms");
            }

            $dbInstance = Connect::getInstance($this->database);
            if(!$dbInstance){
                throw new \PDOException("Couldn't find the connection array informations");
            }
            $stmt = $dbInstance->prepare($query);
            if ($this->params) {
                $stmt->execute($this->params);
                $this->afterDelete();
                return true;
            }

            $stmt->execute();

            $this->afterDelete();
            return true;
        } catch (\PDOException $exception) {
            $this->fail = $exception;
            $this->log->critical(__METHOD__, $exception->getTrace());
            $this->setMessage('error', "Ocorreu uma falha");
            return false;
        }
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     *
     * @return bool
     */
    public function delete(string $terms = null, string $params = null):bool
    {
        if($terms){
            $this->where($terms, $params);
        }

        if(empty($this->terms)){
            $this->setMessage('error', "Operação de DELETE inválida no banco de dados.");
            return false;
        }

        $softDeleteColumnName = $this->softDeleteColumnName;
        if($this->softDelete && empty($this->$softDeleteColumnName)){
            if(user() && in_array('deleted_by', $this->getColumns())){
                $extraData['deleted_by'] = user()->id;
            }
            $extraData[$this->softDeleteColumnName] = date("Y-m-d H:i:s");
            $this->where("{$this->softDeleteColumnName} IS NULL");
            return $this->update($extraData);
        }
        return $this->forceDelete();

    }

    /**
     * This method is used to destroy de active record.
     *
     * @return bool
     */
    public function destroy():bool
    {
        if (empty($this->id)) {
            return false;
        }

        $destroy = $this->delete("id = :id", "id={$this->id}");

        return $destroy;
    }

    /**
     * Unset protect fields from data object and prevent to be updated
     *
     * @return array
     */
    protected function safe():array
    {
        $safe = (array)$this->data;
        foreach ($this->protected as $unset) {
            if(in_array($unset, array_keys($safe))){
                unset($safe[$unset]);
            }
        }
        return $safe;
    }

    /**
     * Unset protect fields from data object and prevent to be seted during creationg
     *
     * @return array
     */
    protected function safeOnCreate():array
    {
        $safe = (array)$this->safe();
        foreach ($this->customSafeOnCreate() as $unset) {
            if(in_array($unset, array_keys($safe))){
                unset($safe[$unset]);
            }
        }
        return $safe;
    }

    /**
     * Unset protect fields from data object and prevent to be updated
     *
     * @return array
     */
    protected function safeOnUpdate():array
    {
        $safe = (array)$this->safe();
        foreach ($this->customSafeOnUpdate() as $unset) {
            if(in_array($unset, array_keys($safe))){
                unset($safe[$unset]);
            }
        }
        return $safe;
    }

    /**
     * Return an array with the protected fields during creating.
     *
     * @return array
     */
    protected function customSafeOnCreate():array
    {
        return [];
    }

    /**
     * Return an array with the protected fields during updates.
     *
     * @return array
     */
    protected function customSafeOnUpdate():array
    {
        return [];
    }

    protected function afterCreate():void
    {

    }

    protected function afterUpdate():void
    {

    }

    protected function afterDelete():void
    {

    }

    /**
     * To security purpose, this method is used to filter the array data using fillable attribute or default filter
     *
     * @param array $data
     * @return array
     */
    private function filter(array $data):array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = isset($this->fillable[$key]) && $this->fillable[$key] == "escape"
              ? $value
              : (is_null($value)
                ? null
                : filter_var($value, $this->fillable[$key] ?? FILTER_SANITIZE_STRIPPED)
              );
        }
        return $filter;
    }

    /**
     * This method confirm if all required fields were informed before saving the record in database.
     *
     * @return bool
     */
    protected function required():bool
    {
        $data = (array)$this->data();

        foreach ($this->required as $field) {
            if (empty($data[$field])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Getter method to access the required fields
     *
     * @return array
     */
    public function getRequiredFields():array
    {
        if(empty($this->required)){
            return [];
        }
        return $this->required;
    }

    /**
     * This method supports the join and addModelToJoin saving the alias name of model.
     *
     * @param $alias
     * @param $entity
     */
    protected function addAliasToEntity($alias, $entity):void
    {
        if(!array_key_exists($alias, $this->aliasToEntities)){
            $this->aliasToEntities[$alias] = $entity;
        }
    }

    /**
     * This method supports the query builder, replacing the alias name to the entity name of model.
     *
     * @param $query
     *
     * @return string
     */
    protected function queryTransformFromTo($query):string
    {
        foreach ($this->aliasToEntities as $key => $entity){
            $query = (str_replace("{$key}.", "{$entity}.", $query));
        }

        return $query;
    }

    /**
     * @param string|null $terms
     * @param string      $operator
     */
    protected function terms(?string $terms = null, string $operator = 'AND'):void
    {
        if($terms){
            $this->terms = $this->terms ? " {$this->terms} {$operator} {$terms} " : " WHERE {$terms} ";
        }
    }

    /**
     * @param array|string|null $params
     */
    protected function params(?string $params = null):void
    {
        if(!empty($params) && is_string($params)){
            $params = $this->parseParams($params);
        }

        if(is_array($params)){
            foreach ($params as $key => $value){
                $this->params[$key] = $value;
            }
        }
    }

    /**
     * This method supports the query builder, converting a query string separated by "&" in a array
     * This array will be used to execute the query
     *
     * @param $params
     *
     * @return array
     */
    protected function parseParams($params):array
    {
        $tempParams = explode('&', $params);
        $newParamsArray = [];
        foreach ($tempParams as $tempParam) {
            $tempParams1 = explode("=", $tempParam);
            if(!isset($tempParams1[1])){
                var_dump($tempParams);
                die();
            }

            $newParamsArray[$tempParams1[0]] = $tempParams1[1];
        }

        return $newParamsArray;
    }

    /**
     * INTEGRIDADE RELACIONAL DO BANCO DE DADOS
     *
     * Os 3 metodos abaixo (onInsert, beforeUpdate e beforeDelete) sao executados antes das operacoes de INSERT, UPDATE e DELETE serem executadas no banco
     * elas somente serao executadas se os metodos retornarem TRUE.
     *
     * Portanto se alguma validacao de integridade for necessaria, reescrever esses metodos nas classes filhas.
     */

    /**
     * Esse metodo é chamado antes de se executar um forceDelete().
     * O forceDelete será executado somente se esse retornar true, portanto caso seja necessária
     * realizar alguma checagem para evitar exclusoes que irao causar falha na integridade dos dados
     * reescreva esse metodo filho.
     *
     * UMA BOA PRATICA É USAR O ->mandatoryForceDelete() PARA DELETAR OS DADOS NAS CLASSES FILHAS RELACIONADAS
     * EVITANDO ASSIM QUE O SOFTDELETE DEIXE DADOS ORFAOS NO BANCO
     *
     * Caso o metodo nao seja reescrito, irá retornar true.
     */
    protected function beforeDelete():bool
    {
        return true;
    }

    protected function beforeUpdate():bool
    {
        return true;
    }

    protected function beforeCreate():bool
    {
        return true;
    }

    /**
     * This method is used to filter data by authenticated user access level, increasing security
     *
     * @return $this
     */
    public function filteredDataByAuthUser() : Model
    {
        if(!user()){
            $this->where("true = false");
            return $this;
        }

        return $this;
    }

    public function filteredDataByToday(?string $date_column = 'm.created_at'):Model
    {
        $date_column = $date_column ?? 'm.created_at';
        $date_columnValid = strpos($date_column, ".") !== false ? substr($date_column, strpos($date_column, ".")+1) : $date_column;
        if(in_array($date_columnValid, $this->getColumns())){
            $this->where("{$date_column} >= CURDATE() AND {$date_column} < CURDATE()+1");
        }
        return $this;
    }

    public function filteredDataByYesterday(?string $date_column = 'm.created_at'):Model
    {
        $date_column = $date_column ?? 'm.created_at';
        $date_columnValid = strpos($date_column, ".") !== false ? substr($date_column, strpos($date_column, ".")+1) : $date_column;
        if(in_array($date_columnValid, $this->getColumns())){
            $this->where("{$date_column} >= CURDATE()-1 AND {$date_column} < CURDATE()");
        }
        return $this;
    }

    public function filteredDataByCurrentMonth(?string $date_column = 'm.created_at'):Model
    {
        $date_column = $date_column ?? 'm.created_at';
        $date_columnValid = strpos($date_column, ".") !== false ? substr($date_column, strpos($date_column, ".")+1) : $date_column;
        if(in_array($date_columnValid, $this->getColumns())){
            $this->where("{$date_column} BETWEEN CONCAT(YEAR(CURDATE()),'-',MONTH(CURDATE()),'-01') AND CURDATE()+1");
        }
        return $this;
    }

    public function filteredDataByLastXDays(?string $date_column = 'm.created_at', int $days = 30):Model
    {
        $date_column = $date_column ?? 'm.created_at';
        $date_columnValid = strpos($date_column, ".") !== false ? substr($date_column, strpos($date_column, ".")+1) : $date_column;
        if(in_array($date_columnValid, $this->getColumns())){
            $this->where("{$date_column} BETWEEN CURRENT_DATE - INTERVAL {$days} DAY AND CURDATE()+1");
        }
        return $this;
    }

    public function actives(?string $active_column = 'm.active'):Model
    {
        $active_column = $active_column ?? 'm.active';
        $active_column = strpos($active_column, ".") !== false ? substr($active_column, strpos($active_column, ".")+1) : $active_column;
        if(in_array($active_column, $this->getColumns())){
            $this->where("{$active_column} = :actives", "actives=1");
        }
        return $this;
    }

    public function whereDateInterval(string $field)
    {

    }

    /**
     * If entity not explicite defined, this method will pluralize the model
     * name and add the optional constant prefix CHAMPS_DB_PREFIX
     */
    protected function entityName():void
    {
        if(!$this->entity){
            $arrayClassName = explode("\\", get_class($this));
            $className = end($arrayClassName);
            $entityGroup = $arrayClassName[count($arrayClassName)-2] != "Models" ? $arrayClassName[count($arrayClassName)-2] : null;
            $model = "";
            for ($i=0 ; $i < strlen($className); $i++){
                $model .= ($i == 0) ? strtolower($className[$i]) : (ctype_upper($className[$i]) ? "_" . strtolower($className[$i]) : $className[$i]);
            }
            $prefix = (defined("CHAMPS_DB_PREFIX") && !empty(CHAMPS_DB_PREFIX) ? CHAMPS_DB_PREFIX  : "");
            $group = $entityGroup ? strtolower($entityGroup)."_" : "";
            $this->entity = $prefix . $group . pluralize($model);
        }
    }

    /**
     * This method is used to created a forced data scope in main model.
     *
     * It will get an array with fieldName, value and operator in method forcedTerms and if the field is already present in term, it
     * will be preserved, if it is not present, it will be included in where clause.
     */
    protected function forcedFilter():void
    {
        foreach ($this->forcedTerms() as $values){
            if (!isset($values['field']) || !isset($values['value'])){
                continue;
            }
            $value = $values['value'];
            $operator = !isset($values['operator']) ? "=" : $values['operator'];
            $fieldName = (strstr($values['field'], '.') ? str_replace('.', '', strstr($values['field'], '.')) : $values['field']);
            if (strpos($this->terms, $fieldName) === false){
                $this->where("m.{$fieldName}{$operator}:forced_{$fieldName}", "forced_{$fieldName}={$value}");
            }
        }
    }

    /**
     * This method is used to created a forced data scope in main model.
     *
     * Create this method in child class and return an array like bellow
     *
     * return ["field" => "datatable_field", "value" => "default_value", "operator" => "mysql operator =, <>, >=, <=, IS NULL, IS NOT NULL"]
     *
     * operator is optional, if it is absent = will be used
     *
     * @return array
     */
    protected function forcedTerms():array
    {
        return [];
    }


}