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

    /**
     * UserHasRole constructor.
     */
    public function __construct()
    {
        parent::__construct("auth_user_has_roles", ["id"], ["user_id", "role_id"]);
    }

    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model
     */
    public function user() : Model
    {
        return (new Auth())->findById($this->user_id);
    }

    /**
     * @return \BetoCampoy\ChampsFramework\ORM\Model
     */
    public function role() :Model
    {
        return (new Role())->findById($this->role_id);
    }

}