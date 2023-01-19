<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class LegacySupportOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Activate the legacy support mode. This feature create a route for legacy pages, 
    but they probably will need some customization!"];
    }

    public function getSection(): string
    {
        return "legacy support";
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