<?php

namespace BetoCampoy\ChampsFramework\Models\Auth;


use BetoCampoy\ChampsFramework\ORM\Model;

/**
 * Class UserHasRole
 *
 * @package BetoCampoy\ChampsModel\Auth
 */
class UserHasRole extends Model
{
    protected ?string $entity = "auth_user_has_roles";
    protected array $protected = ["id"];
    protected array $required = ["user_id", "role_id"];

    /**
     * @return Model
     */
    public function user() : Model
    {
        return (new User())->findById($this->user_id);
    }

    /**
     * @return Model
     */
    public function role() :Model
    {
        return (new Role())->findById($this->role_id);
    }

}