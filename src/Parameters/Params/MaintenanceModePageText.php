<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MaintenanceModePageText extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the text of maintenance mode page!"];
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
        return "We'll be back soon! For now we are working to improve our system :P";
    }

    public function getValidValues(): array
    {
        return [];
    }
}