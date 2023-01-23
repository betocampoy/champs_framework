<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


/**
 *
 * This parameter define the of Database Entity responsible to store the users.
 * The default value is auth_users
 *
 * Class AuthClassEntity
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class AuthModelEntity extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Change the database table that store the users!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication general";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return 'auth_users';
    }

    public function getValidValues(): array
    {
        return [];
    }
}