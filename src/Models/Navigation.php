<?php

namespace BetoCampoy\ChampsFramework\Models;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class Navigation
 *
 * @package BetoCampoy\ChampsFramework\Models
 */
class Navigation extends Model
{
    protected array $protected = ["id"];
    protected array $nullable = ["parent_id"];
    protected array $required = ["theme", "display_name", "sequence"];
    protected ?string $entity = "navigation";
    protected string $theme = CHAMPS_VIEW_WEB;

    /**
     * Define navigation theme. The default theme is web
     *
     * @param string $theme
     * @return Model
     */
    public function setTheme(string $theme):Model
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model|null
     */
    public function children():?Model
    {
        return $this->hasMany(Navigation::class, 'parent_id')
            ->where("visible = 1")
            ->order("sequence ASC");
    }

    /**
     * Static method to find all root itens of theme navigation
     *
     * @return Model
     */
    public static function rootItens()
    {
        return (new Navigation())
            ->filteredTheme()
            ->where("(parent_id IS NULL OR parent_id = 0) AND visible = 1")
            ->order("sequence ASC");
    }

    public function fill(array $data = []): Model
    {
        $parent_id = isset($data['parent_id']) ? filter_var($data['parent_id'], FILTER_SANITIZE_NUMBER_INT) : null;
        $sequence = isset($data['sequence']) ? filter_var($data['sequence'], FILTER_SANITIZE_NUMBER_INT) : null;
        $nextSequence = $this->nextSequence($parent_id);

        if($this->oldData()){
            //update

            // se alterar o parent_id, incluir o registro na proxima sequencia
            if((int)$this->data()->parent_id != (int)$parent_id){
                $data['sequence'] = $nextSequence;
            }
            elseif($sequence > $nextSequence){
                $data['sequence'] = $nextSequence - 1;
            }
        }else{
            //create
            if(!$sequence || $sequence > $nextSequence){
                $data['sequence'] = $nextSequence;
            }
        }

        return parent ::fill($data);
    }

    public function reorganize(?string $theme = 'web', ?int $parent_id = null):void
    {
        $navsToReorder = (new Navigation())->filteredTheme()->order("sequence ASC");//->where("sequence >= :sequence", "sequence={$sequence}");
        if($parent_id){
            $navsToReorder->where("parent_id=:parent_id", "parent_id={$parent_id}");
        }else{
            $navsToReorder->where("parent_id IS NULL");
        }

        $idx = 1;
        foreach ($navsToReorder->fetch(true) as $reoder){

            if((int)$reoder->sequence != (int)$idx){
                $reoder->sequence = $idx;
                $reoder->save(false);
            }

            $idx++;
        }
    }

    protected function nextSequence(?int $parent_id = null):?int
    {
        if(!$parent_id){
            $rootItens = (Navigation::rootItens())
              ->columns("sequence")
              ->order("sequence DESC")
              ->fetch();
            return $rootItens ? $rootItens->sequence + 1 : 1;
        }

        $nav = (new Navigation())
          ->where("parent_id = :parent_id", "parent_id={$parent_id}")
          ->columns("sequence")
          ->order("sequence DESC")
          ->fetch();

        return $nav ? $nav->sequence + 1 : 1;
    }

    protected function beforeCreate(): bool
    {
        $navs = (new Navigation())->setTheme($this->theme)->where("sequence >= :sequence", "sequence={$this->sequence}")->order("sequence DESC");
        if($this->parent_id){
            $navs->where("parent_id=:parent_id", "parent_id={$this->parent_id}");
        }else{
            $navs->where("parent_id IS NULL");
        }

        if($navs->count() == 0){
            return true;
        }

        foreach ($navs->fetch(true) as $nav){
            $nav->sequence = $nav->sequence + 1;
            $nav->save(false);
        }

        return true;
    }

    protected function beforeUpdate(): bool
    {
        if($this->dataChanged(['parent_id'])){

            if($this->parent_id == $this->id){
                $this->setMessage('error', "O item não pode ser filho dele mesmo");
                return false;
            }
            return true;
        }

        if($this->dataChanged(['sequence'])){
            // create de object
            $navs = (new Navigation())->setTheme($this->theme);

            // filter by parent_id
            if($this->parent_id){
                $navs->where("parent_id=:parent_id", "parent_id={$this->parent_id}");
            }else{
                $navs->where("parent_id IS NULL");
            }

            // remove the changed record of selection
            $navs->where("sequence <> :sequence",
              "sequence={$this->oldData()->sequence}");

            // change sequence of affected database records
            if((int)$this->oldData()->sequence < (int)$this->sequence){
                $navs->where("sequence BETWEEN :sequence_start AND :sequence_end",
                  "sequence_start={$this->oldData()->sequence}&sequence_end={$this->sequence}");
                $increment = false;
            }else{
                $navs->where("sequence BETWEEN :sequence_start AND :sequence_end",
                  "sequence_start={$this->sequence}&sequence_end={$this->oldData()->sequence}");
                $increment = true;
            }

            // define the order of dataset
            $navs->order("sequence DESC");

            foreach ($navs->fetch(true) as $nav){
                if($increment){
                    $nav->sequence = $nav->sequence + 1;
                }else{
                    $nav->sequence = $nav->sequence - 1;
                }
                $nav->save(false);
            }
            return true;
        }

        return true;
    }

    protected function afterUpdate(): void
    {
        if($this->dataChanged(['parent_id'])){
            $this->reorganize($this->oldData()->parent_id);
        }
    }

    /**
     * Scope by theme
     *
     * @param string $theme
     * @return Model
     */
    public function filteredTheme():Model
    {
        return $this->where("theme=:theme", "theme={$this->theme}");
    }
}