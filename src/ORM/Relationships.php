<?php


namespace BetoCampoy\ChampsFramework\ORM;


trait Relationships
{
    /**
     * METODOS BASE PARA CRIACAO DOS RELACIONAMENTOS ENTRE OS MODELOS
     *
     * Os metodos a seguir, sao metodos de relacionamento. Eles
     * simplificam e padronizam a criaçao dos relacionamentos entre os modelos e
     * para utiliza-los, basta criar um metodo responsavel pelo relacionamento no modelo origem,
     * fazendo que retorne o relacionamento desejado.
     *
     * examplo:
     * public function cliente():?Model
     * {
     *    return $this->hasOne(Usuario::class);
     * }
     *
     * estrutura
     *
     * $cliente -> belongsToMany ( Usuario::class , ClienteUsuario::class, $through_key, $local_key, $search_id, $columns, $adicional_terms, $adicional_params)
     *     |            |               |                |                      |          |           |           |            |                |
     *     |            |               |                |                      |          |           |           |            |
     *     |            |               |                |                      |          |           |           |
     *     |            |               |                |                      |          |           |
     *     |            |               |                |                      |          |
     *     |            |               |                |                      |
     *     |            |               |                |- $through_model_class: Modelo intermediario que controla o relacionamento
     *     |            |               |- $related_model_class é o modelo com o qual o modelo principal irá se relacionar
     *     |            |- tipo de relacionamento desejado
     *     |- modelo principal
     * $modeloPrincipal->belongsToMany
     */

    /**
     * Utilizado para criar o relacionamento one-to-one
     *
     * IMPORTANTE: a FK esta no modelo relacionado
     *
     *
     * @param string      $related_model_class
     * @param string|null $foreign_key
     *
     * @return Model|null
     */
    protected function hasOne(string $related_model_class, string $foreign_key = null): ?Model
    {
//        if (empty($this->id)) {
//            return null;
//        }

        if (!$foreign_key) {
            $arClass = explode('\\', strtolower(get_class($this)));
            $foreign_key = $arClass[count($arClass) - 1] . "_id";
        }

        $relatedModel = (new $related_model_class);
        if (!in_array($foreign_key, $relatedModel->getColumns())) {
            return null;
        }

        return $relatedModel->where("{$foreign_key}=:relat_{$foreign_key}", "relat_{$foreign_key}={$this->id}")->fetch();
    }

    /**
     * Utilizado para criar o relacionamento one-to-one
     *
     * IMPORTANTE: a FK esta no modelo local
     *
     * @param string      $related_model_class
     * @param string|null $foreign_key
     *
     * @return Model|null
     */
    protected function belongsTo(string $related_model_class, string $foreign_key = null): ?Model
    {
        //        if(empty($this->id)){
        //            return null;
        //        }

        if(!$foreign_key){
            $arClass = explode('\\', strtolower($related_model_class));
            $foreign_key = $arClass[count($arClass)-1]."_id";
        }

        if(empty($this->$foreign_key)){
            return null;
        }

        $model = (new $related_model_class)->findById($this->$foreign_key);
        var_dump($model);die();
        return empty($model) ? null : $model;
    }

    /**
     * Utilizado para criar o relacionamento one-to-many
     *
     * @param string      $related_model_class
     * @param string|null $foreign_key
     *
     * @return Model|null
     */
    protected function hasMany(string $related_model_class, string $foreign_key = null):?Model
    {
        if(empty($this->id)){
            return null;
        }

        if(!$foreign_key){
            $arClass = explode('\\', strtolower(get_class($this)));
            $foreign_key = $arClass[count($arClass)-1]."_id";
        }

        //        return (new $related_model_class)->find("{$foreign_key}=:{$foreign_key}", "{$foreign_key}={$this->id}");
        return (new $related_model_class)->find("{$foreign_key}=:foreign_key", "foreign_key={$this->id}");
    }

    /**
     * Utilizado para criar o relacionamento many-to-many
     *
     * esse metodo de relacionamento many-to-many retorna uma instancia do modelo RELACIONADO
     *
     * @param string $related_model_class
     * @param string $through_model_class
     * @param string|null $through_key
     * @param string|null $local_key
     * @param int|null $search_id
     * @param string|null $columns
     * @param string|null $adicional_terms
     * @param string|null $adicional_params
     * @param bool $cascadeDelete
     * @return Model|null
     */
    protected function hasManyThrough(
        string $related_model_class,
        string $through_model_class,
        string $through_key = null,
        string $local_key = null,
        int $search_id = null,
        string $columns = null,
        string $adicional_terms = null,
        string $adicional_params = null,
        bool $cascadeDelete = true
    ): ?Model
    {

        if(empty($this->id)){
            return null;
        }

        if(!$through_key){
            $arClass = explode('\\', strtolower($related_model_class));
            $through_key = $arClass[count($arClass)-1]."_id";
        }

        if(!$local_key){
            $arOwnerClass = explode('\\', strtolower(get_class($this)));
            $local_key = $arOwnerClass[count($arOwnerClass)-1]."_id";
        }

        $through_model_alias_name = $cascadeDelete ? "through" : "j";

        $throughModel = new $through_model_class;

        if(!$columns){
            $columns = "m.*";
            foreach ($throughModelColumns = $throughModel->getColumns() as $col){
                $col = $col == "id" ? "through.id as join_id" : "through.{$col}";
                $columns = "{$columns}, {$col}";
            }
        }

        /** @var Model $model */
        $model = (new $related_model_class);
        $model->find("through.{$local_key}=:{$local_key}", "{$local_key}={$this->id}", $columns )
          ->join($through_model_class, "m.id=through.{$through_key}", null, null, "through");

        if($search_id){
            $model->where("m.id=:through_key", "through_key={$search_id}");
        }

        if($adicional_terms){
            $model->where($adicional_terms, $adicional_params);
        }

        return $model;
    }

    /**
     * Utilizado para criar o relacionamento many-to-many
     *
     * esse metodo de relacionamento many-to-many retorna uma instancia do modelo INTERMEDIARIO
     *
     * @param string      $related_model_class
     * @param string      $intermediate_model_class
     * @param string|null $foreign_key
     * @param string|null $local_key
     * @param int|null    $search_id
     * @param string|null $columns
     *
     * @return Model|null
     */
    protected function belongsToMany(string $related_model_class, string $intermediate_model_class, string $foreign_key = null, string $local_key = null, int $search_id = null, string $columns = null): ?Model
    {
        if(empty($this->id)){
            return null;
        }

        if(!$foreign_key){
            $arClass = explode('\\', strtolower($related_model_class));
            $foreign_key = $arClass[count($arClass)-1]."_id";
        }

        if(!$local_key){
            $arOwnerClass = explode('\\', strtolower(get_class($this)));
            $local_key = $arOwnerClass[count($arOwnerClass)-1]."_id";
        }

        $columns = $columns ?? "*";

        /** @var Model $model */
        $model = (new $intermediate_model_class);
        $model->find("{$local_key}=:{$local_key}", "{$local_key}={$this->id}", $columns);

        if($search_id){
            $model->where("{$foreign_key}=:foreign_key", "foreign_key={$search_id}");
        }

        return $model;

    }

    /**
     * Metodo de consulta na tabela intermediaria, com ele é possivel verificar se o modelo principal
     * tem relacao com o modelo relacionado.
     *
     * @param string      $manyToManyMethod
     * @param int         $id
     * @param string|null $colunms
     * @param string|null $terms
     * @param string|null $params
     *
     * @return bool
     */
    public function has(string $manyToManyMethod, int $id, string $colunms = null, string $terms = null, string $params = null):bool
    {
        $collection = $this->$manyToManyMethod($id, $colunms, $terms, $params);

        if(!$collection){
            return false;
        }

        if($collection->count() > 0){
            return true;
        }

        return false;
    }

    /**
     * Atualizar dados na tabela de relacionamentos manyToMany.
     *
     * Uso: partindo do modelo de origem que possua um relacionamento manyToMany, basta chamar o metodo sync() com
     * os paramentros abaixo e o metodo irá atualizar os dados na tabela intermediária.
     * Exemplo, temos uma modelo Cliente e um modelo Usuario, e entre eles um relacionamento onde cliente pode ter
     * varios usuarios e um usuario pode pertencer a varios cliente. Entao faz-se necessario um modelo intermediario
     * ClienteUsuario com no mínimo os campos id, cliente_id, usuario_id
     *
     * Cliente()->sync(ClienteUsuario::class, Usuario::class, [1,2,3,4])
     *
     * @param string      $intermediate_model_class // informe o modelo que controla a tabela intermediária
     * @param string      $related_model_class // modelo que controla a outra ponta do relacionamento
     * @param array       $array_ids // array dos id que serao syncronizados [1,2,3,4,10]
     * @param array       $aditional_data // caso a tabela intermediaria tenha camppos adicionais que precisam ser preenchidos
     * @param string|null $foreign_key // nome do campo id do modelo relacionado. Por padrao será utilizado o nome o modelo em minuscula _id, portanto informar somente ser for diferente do padrao
     * @param string|null $local_key // nome do campo id do modelo principal. Por padrao será utilizado o nome o modelo em minuscula _id, portanto informar somente ser for diferente do padrao
     * @param string|null $aditional_terms_to_delete_main_model // caso seja necessário termos adicionais para localizar os dados na tabela intermediaria
     * @param string|null $aditional_params_to_delete_main_model // caso seja necessário parametros adicionais para localizar os dados na tabela intermediaria
     *
     * @return bool
     */
    public function sync(string $intermediate_model_class, string $related_model_class, ?array $array_ids = [], ?array $aditional_data = [], string $foreign_key = null, string $local_key = null, string $aditional_terms_to_delete_main_model = null, string $aditional_params_to_delete_main_model = null) :bool
    {

        if(empty($aditional_data) || !is_array($aditional_data)){
            $aditional_data = [];
        }

        if(!$foreign_key){
            $arClass = explode('\\', strtolower($related_model_class));
            $foreign_key = $arClass[count($arClass)-1]."_id";
        }

        if(!$local_key){
            $arOwnerClass = explode('\\', strtolower(get_class($this)));
            $local_key = $arOwnerClass[count($arOwnerClass)-1]."_id";
        }

        // abrir transação

        // delete todos os registros do modelo principal
        $manyToManyModel = new $intermediate_model_class;
        $delete_terms = $aditional_terms_to_delete_main_model ? "{$local_key}=:id AND {$aditional_terms_to_delete_main_model}" : "{$local_key}=:id";
        $delete_params = $aditional_params_to_delete_main_model ? "id={$this->id}&{$aditional_params_to_delete_main_model}" : "id={$this->id}";
        if(!$manyToManyModel->delete($delete_terms, $delete_params)){
            return false;
        }

        if(!is_array($array_ids)){
            return false;
        }

        // insere os novos registros
        $manyToManyModel->$local_key = $this->id;

        foreach ($array_ids as $id){
            $manyToManyModel->$foreign_key = $id;
            foreach ($aditional_data as $field => $value){
                $manyToManyModel->$field = $value;
            }

            try {

                if(!$manyToManyModel->save()){
                    // faz o rollback
                    return false;
                }
            }catch (\Exception $e){
                // faz o rollback
                return false;
            }

            $manyToManyModel->id = null;
        }

        // fechar transação
        return true;

    }

    public function syncIdsOnRelatedModel(string $childModel, string $parentKey, int $parentId, ?array $data_input = [], ?array $aditional_data = [], string $aditional_terms = null, string $aditional_params = null, string $input_prefix = null):bool
    {
        if(empty($aditional_data) || !is_array($aditional_data)){
            $aditional_data = [];
        }

        if(empty($data_input) || !is_array($data_input)){
            return false;
        }

        $prefix = $input_prefix ?? str_replace("_id", '_', $parentKey);

        $terms_ids = null;
        $params_ids = null;
        foreach ($data_input as $key => $item){
            if(strstr($key, $prefix)){
                $new_id = str_replace($prefix, '', $key);
                $terms_ids = $terms_ids ? "{$terms_ids}, :id_{$new_id}" : ":id_{$new_id}";
                $params_ids = $params_ids ? "{$params_ids}&id_{$new_id}={$new_id}" : "id_{$new_id}={$new_id}";
            }
        }

        try {

            /* set relatedkey to null in child model */
            /** @var Model $relatedChildModel */
            $relatedChildModel = (new $childModel);
            if($aditional_terms){
                $relatedChildModel->where($aditional_terms, $aditional_params);
            }
            $result = $relatedChildModel->update([$parentKey => null], "{$parentKey}=:parent_id", "parent_id={$parentId}");

            if(empty($terms_ids) || empty($params_ids)){
                return true;
            }

            /* update the child model whith new data */
            $relatedChildModelIns = (new $childModel);
            $upt_data = !empty($aditional_data) ? array_merge([$parentKey => $parentId], $aditional_data) : [$parentKey => $parentId];
            $relatedChildModelIns->update($upt_data, "id IN ({$terms_ids})", $params_ids);

        }catch (\Exception $e){
            // faz o rollback
            return false;
        }

        // fechar transação
        return true;

    }

    /**
     * Same the sync, but in this method don't receive an array of ids. Instead of, you will pass all input data array and it will filter the ids based in prefix
     *
     * @param string      $intermediate_model_class
     * @param string      $related_model_class
     * @param array       $data_input
     * @param array       $aditional_data
     * @param string|null $foreign_key
     * @param string|null $local_key
     * @param string|null $aditional_terms_to_delete_main_model
     * @param string|null $aditional_params_to_delete_main_model
     * @param string|null $input_prefix
     *
     * @return bool
     */
    public function syncWithInput(string $intermediate_model_class, string $related_model_class, ?array $data_input = [], ?array $aditional_data = [], string $foreign_key = null, string $local_key = null, string $aditional_terms_to_delete_main_model = null, string $aditional_params_to_delete_main_model = null, string $input_prefix = null) :bool
    {

        if(empty($aditional_data) || !is_array($aditional_data)){
            $aditional_data = [];
        }

        if(empty($data_input) || !is_array($data_input)){
            return false;
        }

        if(!$foreign_key){
            $arClass = explode('\\', strtolower($related_model_class));
            $foreign_key = $arClass[count($arClass)-1]."_id";
        }

        if(!$local_key){
            $arOwnerClass = explode('\\', strtolower(get_class($this)));
            $local_key = $arOwnerClass[count($arOwnerClass)-1]."_id";
        }

        $prefix = $input_prefix ?? str_replace("_id", '_', $foreign_key);
        $array_ids = [];
        foreach ($data_input as $key => $item){
            if(strstr($key, $prefix)){
                $array_ids[] = str_replace($prefix, '', $key);
            }
        }

        if(!is_array($array_ids)){
            return false;
        }

        // abrir transação

        // delete todos os registros do modelo principal
        $manyToManyModel = new $intermediate_model_class;
        $delete_terms = $aditional_terms_to_delete_main_model ? "{$local_key}=:id AND {$aditional_terms_to_delete_main_model}" : "{$local_key}=:id";
        $delete_params = $aditional_params_to_delete_main_model ? "id={$this->id}&{$aditional_params_to_delete_main_model}" : "id={$this->id}";
        if(!$manyToManyModel->delete($delete_terms, $delete_params)){
            return false;
        }

        // insere os novos registros
        $manyToManyModel->$local_key = $this->id;

        foreach ($array_ids as $id){
            $manyToManyModel->$foreign_key = $id;
            foreach ($aditional_data as $field => $value){
                $manyToManyModel->$field = $value;
            }

            try {
                if(!$manyToManyModel->save()){
                    // faz o rollback
                    return false;
                }
            }catch (\Exception $e){
                // faz o rollback
                return false;
            }

            $manyToManyModel->id = null;
        }

        // fechar transação
        return true;

    }

}