<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SystemEnvironmentIdentifier extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "select";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the environment where the app is current running"];
    }

    public function getSection(): string
    {
        return "system";
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
        return ["Development" => "DEV", "Tests" => "UAT", "Production" => "PRD"];
    }
}