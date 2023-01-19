<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MaintenanceModeOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Put system under maintenance mode!"];
    }

    public function getSection(): string
    {
        return "maintenance mode";
    }

    public function getValue():bool
    {
        return $this->value;
    }

    public function getDefaultValue():bool
    {
        return false;
    }

    public function getValidValues(): array
    {
        return [];
    }
}