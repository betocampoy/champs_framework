<?php

namespace BetoCampoy\ChampsFramework\Models\Auth;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class AccessLevel
 *
 * @package BetoCampoy\ChampsModel\Auth
 */
class AccessLevel extends Model
{
    protected ?string $entity = "auth_access_levels";
    protected array $protected = ["id"];
    protected array $required = ["name"];

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string      $columns
     * @param string      $order
     *
     * @return Model
     */
    public function users(string $terms = null, string $params = null, string $columns = 'id', string $order = 'id ASC') :Model
    {
        return (new User())->find($terms, $params, $columns)->order($order);
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string      $columns
     * @param string      $order
     *
     * @return Model
     */
    public function roles(string $terms = null, string $params = null, string $columns = 'id', string $order = 'id ASC') :Model
    {
        return (new Role())->find($terms, $params, $columns)->order($order);
    }

    /**
     * @return Model
     */
    public function filteredDataByAuthUser() : Model
    {
        if(!user()){
            $this->where("true = false");
            return $this;
        }

        $access_level_id = user()->access_level_id;
        $this->where("id >= :access_level_id", "access_level_id={$access_level_id}");

        return $this;
    }
}