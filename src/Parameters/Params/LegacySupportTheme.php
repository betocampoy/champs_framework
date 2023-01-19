<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


use BetoCampoy\ChampsFramework\Controller\LegacyController;

class LegacySupportTheme extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the dir inside folder theme where legacy pages will be stored!"];
    }

    public function getSection(): string
    {
        return "legacy support";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "legacy-pages";
    }

    public function getValidValues(): array
    {
        return [];
    }
}