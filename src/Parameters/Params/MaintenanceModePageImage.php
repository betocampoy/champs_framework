<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MaintenanceModePageImage extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected array $dependencies = [
        SystemUrlProject::class
    ];

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the image of maintenance mode page!"];
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
        return __champshelp_theme("/assets/images/developer.svg");
    }

    public function getValidValues(): array
    {
        return [];
    }
}