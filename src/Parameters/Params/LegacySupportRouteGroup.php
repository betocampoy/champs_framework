<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


use BetoCampoy\ChampsFramework\Controller\LegacyController;

class LegacySupportRouteGroup extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Optionally define a route group for legacy urls!"];
    }

    public function getSectionGroup(): string
    {
        return "legacy support";
    }

    public function getSection(): string
    {
        return "legacy support";
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getDefaultValue(): ?string
    {
        return null;
    }

    public function getValidValues(): array
    {
        return [];
    }
}