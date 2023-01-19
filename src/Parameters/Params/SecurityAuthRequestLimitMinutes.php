<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SecurityAuthRequestLimitMinutes extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define how many minutes the user should wait before attempt to login again!"];
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
        return 5;
    }

    public function getValidValues(): array
    {
        return [];
    }
}