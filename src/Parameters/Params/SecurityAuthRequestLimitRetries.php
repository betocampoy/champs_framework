<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SecurityAuthRequestLimitRetries extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the how many times the user can attempt to login!"];
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
        return 3;
    }

    public function getValidValues(): array
    {
        return [];
    }
}