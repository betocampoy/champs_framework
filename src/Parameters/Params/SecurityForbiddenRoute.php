<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class SecurityForbiddenRoute extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the forbidden route!"];
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
        return "/uhups/error/forbidden";
    }

    public function getValidValues(): array
    {
        return [];
    }
}