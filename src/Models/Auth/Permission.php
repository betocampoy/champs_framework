<?php

namespace BetoCampoy\ChampsFramework\Models\Auth;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class Permission
 *
 * @package BetoCampoy\ChampsModel\Auth
 */
class Permission extends Model
{
    protected ?string $entity = "auth_permissions";
    protected array $protected = ["id"];
    protected array $required = ["name"];

    /*
     * #######################
     * ### RELATIONSHIPS   ###
     * #######################
     */

    /**
     * @param int|null    $id
     * @param string|null $colunms
     *
     * @return Model|null
     */
    public function roles(int $id = null, string $colunms = null):?Model
    {
        return $this->hasManyThrough(Role::class, RoleHasPermission::class, null, null, $id, $colunms);
    }

    /**
     * @return Model|null
     */
    public function rolesIntermediateTable():?Model
    {
        return $this->hasMany(RoleHasPermission::class);
    }

    /*
     * ##########################
     * ### PREPARE SET DATA   ###
     * ##########################
     */

    /**
     * @param string|null $value
     *
     * @return string|null
     */
    protected function prepareName(?string $value):?string
    {
        if(!$value){
            return null;
        }
        return ucfirst($value);
    }

    /*
     * #########################
     * ### FORMAT GET DATA   ###
     * #########################
     */


    /*
     * #################
     * ### GETTERS   ###
     * #################
     */

    /*
     * ##################################
     * ### PARENTS REWRITED METHODS   ###
     * ##################################
     */


    protected function onDelete():bool
    {
        if(!$this->rolesIntermediateTable()->mandatoryForceDelete()->delete()){
            return false;
        }
        return true;
    }


    /*
     * #################################
     * ### EXCLUSIVE CLASS METHODS   ###
     * #################################
     */
}