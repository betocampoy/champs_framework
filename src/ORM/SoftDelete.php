<?php


namespace BetoCampoy\ChampsFramework\ORM;


trait SoftDelete
{
    /**
     * By default, softDelete is automaticaly enable if there is $softDeleteColumnName column in database
     *
     * Rewriting this attribute to true in child class, will disable the softDelete instead of the column exists
     *
     * @var bool
     */
    protected $softDeleteForcedDisable = false;

    protected $softDeleteColumnName = 'deleted_at';

    /*
     * This attribute defines the set of data should return by fetch() method.
     * To change its value, use the methods bellow before fetch() or count() methods
     *
     * - Method trashedOnly()
     * - Method withTrashed()
     * - Method untrashedOnly() -> default value
     *
     */
    protected $whereSoftDelete = null;

    /**
     * The constructor checks if the column 'deleted_at exists. If yes, the softDelete is set to true'
     *
     * @var bool $softDelete
     */
    protected $softDelete = false;

    /**
     * METODOS UTILIZADOS PELO SOFTDELETE
     *
     * Os próximos 3 metodos de selecao sao utilizado pelo softDelete (exclusao lógica).
     * Eles devem ser utilizados de forma encadeada
     * e sempre depois do metodo find() e antes do fetch() ou count().
     *
     * Caso o softDelete esteja ativo, será incluido uma clausula no where filtrando somente os dados ativos, apagados ou todos.
     *
     * Se nenhum dos 3 metodos forem chamados o padrao será o untrashedOnly().
     *
     * untrashedOnly() -> retorna somente os dados nao apagados logicamente (padrao)
     * trashedOnly() -> retorna somente os dados da lixeira
     * withTrashed() -> retorna todos os dados
     *
     * Para ativar o softDelete no modelo, basta criar a coluna na tabela, do tipo TIMESTAMP aceitando NULL.
     * O nome padrao dessa coluna é [deleted_at], porém caso queira colocar outro nome, basta reescrever o
     * atributo:
     *
     * protect $softDeleteColumnName = 'nome_desejado' no modelo filho.
     */

    /**
     * @return $this
     */
    public function untrashedOnly():Model
    {
        if($this->softDelete){
            $this->whereSoftDelete = "{$this->softDeleteColumnName} IS NULL";
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function trashedOnly():Model
    {
        if($this->softDelete){
            $this->whereSoftDelete = "{$this->softDeleteColumnName} IS NOT NULL";
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function withTrashed():Model
    {
        if($this->softDelete){
            $this->whereSoftDelete = null;
        }
        return $this;
    }

    /**
     * Esse metodo desativa o softDelete para que o registro seja removido fisicamente do bando de dados
     *
     * @return $this
     */
    public function mandatoryForceDelete():Model
    {
        $this->softDelete = false;
        $this->whereSoftDelete = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTrashed():bool
    {
        if(!$this->id){
            return false;
        }

        $attr = $this->softDeleteColumnName;
        if($this->softDelete && $this->$attr){
            return true;
        }
        return false;
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     *
     * @return bool
     */
    public function restore(string $terms = null, string $params = null):bool
    {

        $this->where($terms, $params);
        $this->where("{$this->softDeleteColumnName} IS NOT NULL");

        if($this->softDelete){
            if(in_array('deleted_by', $this->getColumns())){
                $data['deleted_by'] = null;
            }
            $data[$this->softDeleteColumnName] = null;

            $restore = $this->update($data);
            return $restore;
        }

        return true;

    }
}