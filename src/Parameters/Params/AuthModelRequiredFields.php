<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


/**
 * This parameter define the required fields of Database Entity responsible to store the users.
 * The default value is email e password
 *
 * Class AuthClassEntity
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class AuthModelRequiredFields extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define de fields required in users database. Use ';' to separate the fields!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication general";
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function getDefaultValue(): array
    {
        return ['email', 'password'];
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => array_filter(explode(";", $value))
        ];
    }
}