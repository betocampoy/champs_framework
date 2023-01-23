<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


use BetoCampoy\ChampsFramework\Models\Auth\User;

/**
 * This parameter define the of Model to manage users.
 *
 * Class AuthModel
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class AuthModel extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the model responsible to manage users!"];
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
        return User::class;
    }

    public function getValidValues(): array
    {
        return [];
    }
}