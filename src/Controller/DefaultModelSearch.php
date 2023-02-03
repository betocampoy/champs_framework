<?php


namespace BetoCampoy\ChampsFramework\Controller;



use BetoCampoy\ChampsFramework\Router\Router;

class DefaultModelSearch extends Controller
{
    protected ?string $pathToViews = __DIR__ . "/../../../themes/" . CHAMPS_VIEW_ADM . "/";


    public function search(?array $data)
    {
        $controller = new $data['controller'];

        $json["customFunction"] = ["function" => "functTest", "data" => $data];
        echo json_encode($json);
    }

    public function list(array $data)
    {
        $model = isset($data['model']) ? filter_var($data['model'], FILTER_SANITIZE_STRING) : null;
        $model_key = strtolower($data['model']);
        $terms = isset($data['terms']) ? filter_var($data['terms'], FILTER_SANITIZE_STRING) : "id = :{$model_key}_id";
        $columns = isset($data['columns']) ? filter_var($data['columns'], FILTER_SANITIZE_STRING) : "id, name";
//        $view_format = isset($data['view_format']) ? filter_var($data['view_format'], FILTER_SANITIZE_STRING) : null;
//        $view_format_helpers = isset($data['view_format_helpers']) ? filter_var($data['view_format_helpers'], FILTER_SANITIZE_STRING) : null;
        $order = isset($data['order']) ? filter_var($data['order'], FILTER_SANITIZE_STRING) : "id ASC";
        $model_method = isset($data['model_method']) ? filter_var($data['model_method'], FILTER_SANITIZE_STRING) : null;
        $model_join = isset($data['model_join']) ? filter_var($data['model_join'], FILTER_SANITIZE_STRING) : null;
        $model_join_terms = isset($data['model_join_terms']) ? filter_var($data['model_join_terms'], FILTER_SANITIZE_STRING) : null;
        $model_join_params = isset($data['model_join_params']) ? filter_var($data['model_join_params'], FILTER_SANITIZE_STRING) : null;
        $params = $params = $this->parseParams($terms, $data);

//        $view_format = isset($data['view_format']) ? filter_var($data['view_format'], FILTER_SANITIZE_STRING) : null;
//        $view_format_helpers = isset($data['view_format_helpers']) ? filter_var($data['view_format_helpers'], FILTER_SANITIZE_STRING) : null;
//
//        //        $arColumns = explode(',', str_replace([' ', 'id,'], ["",""], $columns));
//        $arColumns = explode(',', str_replace([' '], [""], $columns));
//        $arHelpers = explode(',', str_replace([' '], [""], $view_format_helpers));

        $fullnamedModel = "\\Source\\Models\\{$model}";
        if(!class_exists($fullnamedModel)){
            $response = $this->setResponse("error", [], 0,"Modelo nao existe");
            echo json_encode($response);
            return;
        }

        /** @var Model $model */
        $model = (new $fullnamedModel());

        if(!$model instanceof Model){
            $response = $this->setResponse("error", [], 0,"Modelo invalido");
            echo json_encode($response);
            return;
        }

        /* verificar se o usuario logado tem acesso ao dados */
        if(!$this->checkAccessPermission($data['model'])){
            $response['redirect'] = url("ops/forbidden");
            echo json_encode($response);
            return;
        }


        $model->filteredDataByAuthUser();
        if($terms){
            $model->where($terms, $params);
        }

        /* se informado join da classe */
        if($model_join && $model_join_terms){
            $fullnamedModelJoin = "\\Source\\Models\\{$model_join}";

            if(!class_exists($fullnamedModel)){
                $response = $this->setResponse("error", [], 0,"Modelo Join nao existe");
                echo json_encode($response);
                return;
            }

            $model->join($fullnamedModelJoin, $model_join_terms, $this->parseParams($model_join_terms, $data));
        }

        /* se informado um metodo da classe */
        if($model_method && method_exists($model, $model_method)){

            $model = $model->fetch();
            if(!$model){
                $response = $this->setResponse("success", [], 0,"Não retornou nenhum valor");
                echo json_encode($response);
                return;
            }

            $model = $model->$model_method()->order($order);
            $counter = $model->count();
            if($counter == 0){
                $response = $this->setResponse("success", [], 0,"Não retornou nenhum valor");
                echo json_encode($response);
                return;
            }

            $responseData = $this->prepareValues($model, $data);

            $response = $this->setResponse("success", $responseData, count($responseData), null);
            echo json_encode($response);
            return;
        }
        else{
            $model->columns($columns);
            if($model->count() == 0){
                $response = $this->setResponse("success", [], 0,"Não retornou nenhum valor");
                echo json_encode($response);
                return;
            }

//            $data = [];
//
//            foreach ($model->fetch(true) as $item){
//                $value = null;
//
//                foreach ($arColumns as $key => $column){
//
//                    if($key == 0 ){
//                        continue;
//                    }
////                    if(strpos($column, 'id') !== false){
////                        continue;
////                    }
//
//                    if (strpos($column, '.') !== false){
//                        $column = str_replace('.', '', strstr($column, '.', false));
//                    }
//
//                    if($view_format){
//                        $value = $value ? $value : $view_format;
//
//                        $item_value = isset($arHelpers[$key]) && !empty($arHelpers[$key]) ? $arHelpers[$key]($item->$column) : $item->$column;
//                        $value = str_replace("[".$column."]", $item_value, $value);
//                    }
//                    else{
//                        $value = $value ? "{$value} | {$item->$column}" : $item->$column;
//                    }
//
//                }
//                $data[$item->id] = $value;
//            }

            $responseData = $this->prepareValues($model, $data);
            $response = $this->setResponse("success", $responseData, count($responseData), null);
            echo json_encode($response);
            return;
        }

    }

    private function prepareValues($model, $data):array
    {
        if($model instanceof Model){
            $modelIsLoaded = false;
            foreach($model->data() as $value){
                $modelIsLoaded = true;
            }
            if($modelIsLoaded){
                $arrayOfModels[] = $model;
            }
            else{
                $arrayOfModels = $model->fetch(true);
            }
        }else{
            $arrayOfModels = $model->fetch(true);
        }

        $columns = isset($data['columns']) ? filter_var($data['columns'], FILTER_SANITIZE_STRING) : "id, name";
        $view_format = isset($data['view_format']) ? filter_var($data['view_format'], FILTER_SANITIZE_STRING) : null;
        $view_format_helpers = isset($data['view_format_helpers']) ? filter_var($data['view_format_helpers'], FILTER_SANITIZE_STRING) : null;

        //        $arColumns = explode(',', str_replace([' ', 'id,'], ["",""], $columns));
        $arColumns = explode(',', str_replace([' '], [""], $columns));
        $arHelpers = explode(',', str_replace([' '], [""], $view_format_helpers));

        $values = [];
        foreach ($arrayOfModels as $item){

            $value = null;

            foreach ($arColumns as $key => $column){

                if($key == 0 ){
                    continue;
                }

                if (strpos($column, '.') !== false){
                    $column = str_replace('.', '', strstr($column, '.', false));
                }

                if($view_format){
                    $value = $value ? $value : $view_format;

                    $item_value = isset($arHelpers[$key]) && !empty($arHelpers[$key]) ? $arHelpers[$key]($item->$column) : $item->$column;
                    $value = str_replace("[".$column."]", $item_value, $value);
                }
                else{
                    $value = $value ? "{$value} | {$item->$column}" : $item->$column;
                }

            }
            $values[$item->id] = $value;
        }
        return $values;
    }

//
//    public function home1(array $data)
//    {
//        $model = isset($data['model']) ? filter_var($data['model'], FILTER_SANITIZE_STRING) : null;
//        $terms = isset($data['terms']) ? filter_var($data['terms'], FILTER_SANITIZE_STRING) : null;
//        $columns = isset($data['columns']) ? filter_var($data['columns'], FILTER_SANITIZE_STRING) : null;
//        $order = isset($data['order']) ? filter_var($data['order'], FILTER_SANITIZE_STRING) : null;
//        $params = null;
//
//        if($terms){
//            $params = $this->parseParams($terms, $data);
//        }
//
//        $model = "\\Source\\Models\\{$model}";
//        $this->queryBuilder($model, $terms, $params , $columns, $order);
//
//        if(!$this->loadedModel){
//            $response = $this->setResponse("error", [], 0,"Modelo invalido");
//            echo json_encode($response);
//            return;
//        }
//
//        if($counter = $this->loadedModel->count() > 0){
//            $data = [];
//
//            foreach ($this->loadedModel->fetch(true) as $item){
//                $data[$item->id] = $item->nome;
//            }
//
//            $response = $this->setResponse("success", $data, $counter, null);
//            echo json_encode($response);
//            return;
//        }
//
//        $response = $this->setResponse("success", [], 0, "Não retornou valores");
//        echo json_encode($response);
//    }

    private function checkAccessPermission(string $model_name, string $permission_type = "List"):bool
    {
        $translate_model_name = [
//          "User" => "Usuarios",
        ];

        $permission_name = isset($translate_model_name[$model_name]) ? "{$translate_model_name[$model_name]} $permission_type" : pluralize($model_name) ." " . $permission_type;

        return hasPermission(str_title($permission_name));
    }

    private function setResponse(string $status, array $data = null,int $count = 0, string $message = null) :array
    {
        return [
          'status' => $status,
          'data' => $data,
          'counter' => $count,
          'message' => $message
        ];
    }

    public function clearAllData():void
    {

    }

    private function parseParams($terms, $data):?string
    {
        if($terms){
            $arTerms = (explode(':', $terms." "));
            unset($arTerms[0]);
            $params = '';
            foreach ($arTerms as $item){
                $item = strstr($item, ' ', 1 );
                $params = $params ? "{$params}&{$item}={$data[$item]}" : "{$item}={$data[$item]}";
            }
        }
        return $params ? $params : null;
    }

//    public function queryBuilder(string $fullnamedModel = null, string $terms = null, string $params = null, string $columns = 'id', string $order = "id")
//    {
//        if(!class_exists($fullnamedModel)){
//            return null;
//        }
//
//        /** @var Model $model */
//        $this->loadedModel = (new $fullnamedModel);
//        if(!in_array("Source\Core\Model", class_parents($this->loadedModel))){
//            return null;
//        }
//
//        $this->loadedModel->filteredDataByAuthUser()->find($terms, $params, $columns)->order($order);
//    }
}