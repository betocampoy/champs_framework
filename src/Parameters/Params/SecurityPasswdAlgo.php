<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SecurityPasswdAlgo extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the password encryption algorithm!"];
    }

    public function getSection(): string
    {
        return "security";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return PASSWORD_DEFAULT;
    }

    public function getValidValues(): array
    {
        return [];
    }
}