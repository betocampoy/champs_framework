<?php

namespace BetoCampoy\ChampsFramework\Models\Auth;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class RoleHasPermission
 *
 * @package BetoCampoy\ChampsModel\Auth
 */
class RoleHasPermission extends Model
{

    /**
     * RoleHasPermission constructor.
     */
    public function __construct()
    {
        parent::__construct("auth_role_has_permissions", ["id"], ["role_id", "permission_id"]);
    }

    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model
     */
    public function role():Model
    {
        return (new Role())->findById($this->role_id);
    }

    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model
     */
    public function permission():Model
    {
        return (new Permission())->findById($this->permission_id);
    }


}