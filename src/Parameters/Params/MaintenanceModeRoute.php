<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MaintenanceModeRoute extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the maintenance mode route!"];
    }

    public function getSectionGroup(): string
    {
        return "maintenance mode";
    }

    public function getSection(): string
    {
        return "maintenance mode page";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "/uhups/maintenance";
    }

    public function getValidValues(): array
    {
        return [];
    }
}