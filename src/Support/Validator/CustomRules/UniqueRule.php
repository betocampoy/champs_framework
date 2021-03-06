<?php

namespace BetoCampoy\ChampsFramework\Support\Validator\CustomRules;

use Rakit\Validation\Rule;
use BetoCampoy\ChampsFramework\ORM\Model;

class UniqueRule extends Rule
{
    protected $message = ":attribute :value has been used";

    protected $fillableParams = ['model', 'column', 'terms', 'params', 'scopes'];

    protected $model;

    public function __construct()
    {

    }

    public function check($value): bool
    {
        // make sure required parameters exists
        $this->requireParameters(['model', 'column']);

        // getting parameters
        $column = $this->parameter('column');
        $modelClass = $this->parameter('model');
//        $except = $this->parameter('except');
        $terms = $this->parameter('terms');
        $params = $this->parameter('params');
        $scopes = explode('&', $this->parameter('scopes'));

        $modelClassApp = "Source\\Models\\{$modelClass}";
        $modelClassVendor = "\\BetoCampoy\\ChampsFramework\\Models\\{$modelClass}";

        if(class_exists($modelClassApp)){
            $modelClass = $modelClassApp;
        }elseif(class_exists($modelClassVendor)){
            $modelClass = $modelClassVendor;
        }else{
            return false;
        }

        /** @var Model $model */
        $model = (new $modelClass);
        $model->find("{$column} = :{$column}", "{$column}={$value}");
        if($terms){
            $model->where($terms, $params);
        }

        foreach ($scopes as $scope){
            if(method_exists($model, $scope)){
                $model->$scope();
            }
        }

        return intval($model->count()) === 0;
    }
}

