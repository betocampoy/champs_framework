<?php

namespace BetoCampoy\ChampsFramework\Support\Validator\CustomRules;

use Rakit\Validation\Rule;
use BetoCampoy\ChampsFramework\ORM\Model;

class FilteredDataByAuthUserRule extends Rule
{
    protected $message = ":attribute :value invalid";

    protected $fillableParams = ['model', 'column', 'terms', 'params'];

    protected $model;

    public function __construct()
    {

    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['model']);

        // getting parameters
        $modelClass = $this->parameter('model');
        $column = $this->parameter('column') ?? "id";
        $terms = $this->parameter('terms');
        $params = $this->parameter('params');

        if(!strstr($modelClass, "\\")){
            $modelClass = "Source\\Models\\{$modelClass}";
        }

        if(!class_exists($modelClass)){
            return false;
        }

        /** @var Model $model */
        $model = (new $modelClass);

        if(is_array($value)){
            foreach ($value as $val){
                $implicite_terms = "{$column} = :p_{$column}";
                $implicite_params = "p_{$column}={$val}";
                $model->filteredDataByAuthUser()->find($implicite_terms, $implicite_params);
                if($terms){
                    $model->where($terms, $params);
                }

                if(intval($model->count()) == 0){
                    return false;
                }
                $model = (new $modelClass);
            }
            return true;

        }

        $implicite_terms = "{$column} = :p_{$column}";
        $implicite_params = "p_{$column}={$value}";

        $model->filteredDataByAuthUser()->find($implicite_terms, $implicite_params);
        if($terms){
            $model->where($terms, $params);
        }

        return intval($model->count()) > 0;
    }
}

