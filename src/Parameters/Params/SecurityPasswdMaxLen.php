<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SecurityPasswdMaxLen extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the maximum length of the password!"];
    }

    public function getSection(): string
    {
        return "security";
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDefaultValue(): int
    {
        return 50;
    }

    public function getValidValues(): array
    {
        return [];
    }
}