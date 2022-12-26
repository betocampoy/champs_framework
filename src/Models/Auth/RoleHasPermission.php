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
    protected ?string $entity = "auth_role_has_permissions";
    protected array $protected = ["id"];
    protected array $required = ["role_id", "permission_id"];

    /**
     * @return Model
     */
    public function role():Model
    {
        return (new Role())->findById($this->role_id);
    }

    /**
     * @return Model
     */
    public function permission():Model
    {
        return (new Permission())->findById($this->permission_id);
    }


}