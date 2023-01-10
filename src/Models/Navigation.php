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
    protected array $nullable = ["parent_id", "route"];
    protected array $required = ["theme_name", "display_name", "sequence"];
    protected ?string $entity = "navigation";
//    protected string $theme = CHAMPS_VIEW_WEB;

//    /**
//     * Define navigation theme. The default theme is web
//     *
//     * @param string $theme
//     * @return Model
//     */
//    public function setTheme(string $themeName): Model
//    {
//        $this->theme = $themeName;
//        return $this;
//    }

    /**
     * @return Model|null
     */
    public function parent(): ?Model
    {
        if (!empty($this->parent_id) && $this->parent_id > 0) {
            return (new Navigation())->findById($this->parent_id) ?? null;
        }
        return null;
    }

    /**
     * @return Model|null
     */
    public function children(): ?Model
    {
        $model = $this->hasMany(Navigation::class, 'parent_id');
        if (!$model) {
            return null;
        }
        return $this->hasMany(Navigation::class, 'parent_id');
    }

    /**
     * Static method to find all root items of theme navigation
     *
     * @param string|null $themeName
     * @param bool $onlyVisible
     * @return Navigation|Model
     */
    public static function rootItems(string $themeName = CHAMPS_VIEW_WEB, bool $onlyVisible = true)
    {
        if ($onlyVisible) {
            return (new Navigation())
                ->where("theme_name=:theme_name", "theme_name={$themeName}")
                ->where("(parent_id IS NULL OR parent_id = 0) AND visible=1")
                ->order("sequence ASC");
        }
        return (new Navigation())
            ->where("theme_name=:theme_name", "theme_name={$themeName}")
            ->where("parent_id IS NULL OR parent_id = 0")
            ->order("sequence ASC");
    }

    /**
     * @return array
     */
    public static function availableThemes(): array
    {
        $themeNames = [];
        foreach ((new Navigation())->columns('DISTINCT (m.theme_name)')->order("theme_name")->fetch(true) as $themeName) {
            $themeNames[] = $themeName->theme_name;
        }
        return $themeNames;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function fill(array $data = []): Model
    {
        $parent_id = isset($data['parent_id']) && (int)$data['parent_id'] > 0
            ? $data['parent_id']
            : null;

        $this->parent_id = $parent_id;
        $this->theme_name = $data['theme_name'];
        $nextSequence = $this->nextSequence();
        $sequence = isset($data['sequence']) ? filter_var($data['sequence'], FILTER_SANITIZE_NUMBER_INT) : $nextSequence;

        $data['sequence'] = $sequence;
        if (isset($this->oldData) && !is_empty($this->oldData)) {
            /* UPDATE */
            // se alterar o parent_id, incluir o registro na proxima sequencia
            if ((int)$this->data()->parent_id != (int)$parent_id) {
                $data['sequence'] = $nextSequence;
            } elseif ($sequence > $nextSequence) {
                $data['sequence'] = $nextSequence - 1;
            }
        } else {
            /* CREATE */
            if (!$sequence || $sequence > $nextSequence) {
                $data['sequence'] = $nextSequence;
            }
        }

//        if (isset($this->oldData) && !is_empty($this->oldData)) {
//            //update
//
//            // se alterar o parent_id, incluir o registro na proxima sequencia
//            if ((int)$this->data()->parent_id != (int)$parent_id) {
//                $data['sequence'] = $nextSequence;
//            } elseif ($sequence > $nextSequence) {
//                $data['sequence'] = $nextSequence - 1;
//            } else {
//                $data['sequence'] = $sequence;
//            }
//
//        } else {
//            //create
//            if (!$sequence || $sequence > $nextSequence) {
//                $data['sequence'] = $nextSequence;
//            } else {
//                $data['sequence'] = $sequence;
//            }
//        }
        parent::fill($data);
        return $this;
    }

    /**
     * @param string $themeName
     * @param int|null $parent_id
     */
    public static function reorganize(string $themeName, ?int $parent_id = null): void
    {
        $navsToReorder = (new Navigation())->order("sequence ASC");//->where("sequence >= :sequence", "sequence={$sequence}");
        if ($parent_id) {
            $navsToReorder->where("theme_name=:theme_name AND parent_id=:parent_id", "theme_name={$themeName}&parent_id={$parent_id}");
        } else {
            $navsToReorder->where("theme_name=:theme_name AND parent_id IS NULL", "theme_name={$themeName}");
        }

        $idx = 1;
        foreach ($navsToReorder->fetch(true) as $reorder) {

            if ((int)$reorder->sequence != (int)$idx) {
                $reorder->sequence = $idx;
                $reorder->save(false);
            }

            $idx++;
        }
    }

    /**
     * @param int|null $parent_id
     * @return int
     */
    protected function nextSequence(): int
    {
        if (!$this->parent_id) {
            $rootItems = (Navigation::rootItems($this->theme_name))
                ->columns("sequence")
                ->order("sequence DESC")
                ->fetch();
            return $rootItems ? $rootItems->sequence + 1 : 1;
        }

        $nav = (new Navigation())
            ->where("theme_name=:theme_name AND parent_id = :parent_id", "theme_name={$this->theme_name}&parent_id={$this->parent_id}")
            ->columns("sequence")
            ->order("sequence DESC")
            ->fetch();

        return $nav ? $nav->sequence + 1 : 1;
    }

    /**
     * @return bool
     */
    protected function beforeCreate(): bool
    {
        $navs = (new Navigation())
            ->where("theme_name=:theme_name AND sequence >= :sequence", "theme_name={$this->theme_name}&sequence={$this->sequence}")
            ->order("sequence DESC");
        if ($this->parent_id) {
            $navs->where("parent_id=:parent_id", "parent_id={$this->parent_id}");
        } else {
            $navs->where("parent_id IS NULL");
        }

        if ($navs->count() == 0) {
            return true;
        }

        foreach ($navs->fetch(true) as $nav) {
            $nav->sequence = $nav->sequence + 1;
            $nav->save(false);
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function beforeUpdate(): bool
    {
        if ($this->dataChanged(['parent_id'])) {

            if ($this->parent_id == $this->id) {
                $this->setMessage('error', "O item não pode ser filho dele mesmo");
                return false;
            }
            return true;
        }

        if ($this->dataChanged(['sequence'])) {
            // create de object
            $navs = (new Navigation())->where("theme_name=:theme_name", "theme_name={$this->theme_name}");

            // filter by parent_id
            if ($this->parent_id) {
                $navs->where("parent_id=:parent_id", "parent_id={$this->parent_id}");
            } else {
                $navs->where("parent_id IS NULL");
            }

            // remove the changed record of selection
            $navs->where("sequence <> :sequence",
                "sequence={$this->oldData()->sequence}");

            // change sequence of affected database records
            if ((int)$this->oldData()->sequence < (int)$this->sequence) {
                $navs->where("sequence BETWEEN :sequence_start AND :sequence_end",
                    "sequence_start={$this->oldData()->sequence}&sequence_end={$this->sequence}");
                $increment = false;
            } else {
                $navs->where("sequence BETWEEN :sequence_start AND :sequence_end",
                    "sequence_start={$this->sequence}&sequence_end={$this->oldData()->sequence}");
                $increment = true;
            }

            // define the order of dataset
            $navs->order("sequence ASC");

            foreach ($navs->fetch(true) as $nav) {
                if ($increment) {
                    $seq = $nav->sequence + 1;
                } else {
                    $seq = $nav->sequence - 1;
                }
                $nav->sequence = $seq;
                $nav->save(false);
            }
            return true;
        }

        return true;
    }

    /**
     *
     */
    protected function afterUpdate(): void
    {
        if ($this->dataChanged(['parent_id'])) {
            $this->reorganize($this->oldData()->theme_name, $this->oldData()->parent_id);
        }
    }

    protected function beforeDelete(): bool
    {
        if (!$this->children()) {
            return true;
        }

        if ($this->children()->count() == 0) {
            return true;
        }

        return $this->children()->delete();
    }


    public function filteredByVisible(): Model
    {
        return $this->where("visible=1");
    }


    public function filteredByThemeName(string $themeName): Model
    {
        return $this->where("theme_name=:theme_name", "theme_name={$themeName}");
    }

    /**
     * PREPARE DATA
     */

    /**
     * @param string $value
     * @return string
     */
    protected function prepareThemeName(string $value): string
    {
        return strtolower($value);
    }

    /**
     * @param string|null $value
     * @return string|null
     */
    protected function prepareRoute(?string $value = null): ?string
    {
        if (!$value) {
            return null;
        }
        return strtolower($value);
    }

    public function __call($name, $arguments)
    {
        var_dump($name, $arguments);
    }

}