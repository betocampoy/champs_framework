<?php


namespace Source\Models\User;

use BetoCampoy\ChampsFramework\ORM\Model;

class User extends Model
{
    public function __construct() {
        parent ::__construct("auth_users", ["id"], ["name", "email", "password"]);
    }

}