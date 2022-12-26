<?php

namespace BetoCampoy\ChampsFramework\Models\Auth;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class Role
 *
 * @package BetoCampoy\ChampsFramework\ORM\Models\Auth
 */
class Role extends Model
{
    protected ?string $entity = "auth_roles";
    protected array $protected = ["id"];
    protected array $required = ["access_level_id", "name"];


    /**
     * @return Model
     */
    public function accessLevel() :Model
    {
        return $this->belongsTo(AccessLevel::class, 'access_level_id');
    }

    /**
     * @param int|null $id
     *
     * @return Model|null
     */
    public function users(int $id = null):?Model
    {
        return $this->hasManyThrough(User::class, UserHasRole::class, 'user_id', null, $id);
    }

    /**
     * @param int|null $id
     *
     * @return Model|null
     */
    public function permissions(int $id = null):?Model
    {
        return $this->belongsToMany(Permission::class, RoleHasPermission::class, null, null, $id);
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     *
     * @return Model|null
     */
    public function hasPermissions(string $terms = null, string $params = null) :?Model
    {
        if($terms){
            $terms = " AND $terms";
        }
        if($params){
            $params = "&$params";
        }

        return (new RoleHasPermission())
          ->find("m.role_id=:role_id{$terms}", "role_id={$this->id}{$params}", "m.*")
          ->join("Source\Models\Auth\Permission", "m.permission_id=j.id");
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission):bool
    {
        $permission = $this->hasPermissions("j.name=:name", "name={$permission}")->count();
        if($permission){
            return true;
        }

        return false;
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

        $access_level_id = user()->accessLevel()->id;
        $this->where("access_level_id >= :access_level_id", "access_level_id={$access_level_id}");

        return $this;
    }

    /**
     * @return bool
     */
    protected function onDelete():bool
    {
        if(!$this->permissions()->mandatoryForceDelete()->delete()){
            return false;
        }
        return true;
    }

}