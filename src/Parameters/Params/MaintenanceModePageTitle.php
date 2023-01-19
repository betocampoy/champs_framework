<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MaintenanceModePageTitle extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the title of maintenance mode page!"];
    }

    public function getSection(): string
    {
        return "maintenance mode page configuration";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "Sorry. We are in maintenance :/";
    }

    public function getValidValues(): array
    {
        return [];
    }
}