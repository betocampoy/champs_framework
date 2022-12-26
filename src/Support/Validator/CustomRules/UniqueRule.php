<?php

namespace BetoCampoy\ChampsFramework\Support\Validator\CustomRules;

use Rakit\Validation\Rule;
use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Valida se o valor é unico no modelo
 *
 * opcoes de uso
 *
 * Opcao 1: Essa é a opcao padrao, quando deseja validar uma coluna na e um valor no modelo
 *    Ex. unique:Modelo,coluna-pesquisada,termos_opicionais,parametros_opcionais,escopo1&escopo2&escopo3
 *
 * Opcao 2: Utilizar essa opcao para validar multiplas colunas no banco de dados
 *    Ex. unique:Modelo,array(),coluna1&coluna2&coluna3,,escopo1&escopo2&escopo3
 *    os valores deverao ser passados como array na mesma ordem informadas nas colunas, para que sejam combinados
 *
 *
 * Class UniqueRule
 * @package Source\Support\Validator\CustomRules
 */
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

        if(!$value) return true;

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

        if (strtolower($column) == "array()"){
            $multiKeys = explode('&', $terms);
            $columns = array_combine($multiKeys, $value);
            foreach ($columns as $key => $value){
                $model->where("{$key}=:multi_{$key}", "multi_{$key}={$value}");
            }
        }else{
            $model->where("{$column} = :{$column}", "{$column}={$value}");

            if($terms){
                $model->where($terms, $params);
            }
        }

        foreach ($scopes as $scope){
            if(method_exists($model, $scope)){
                $model->$scope();
            }
        }

        return intval($model->count()) === 0;
    }
}

