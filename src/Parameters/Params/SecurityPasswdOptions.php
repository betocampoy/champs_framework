<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SecurityPasswdOptions extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define an array assoc with the password option. (Inform the array like this:
        cost=10;time_cost=5) The framework will convert to an properly array!"];
    }

    public function getSection(): string
    {
        return "security";
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function getDefaultValue(): array
    {
        return ["cost" => 10];
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => self::strToArray($value)
        ];
    }
}