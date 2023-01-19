<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MaintenanceModeIpExceptions extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define a list of IP addresses (comma separated) to by-pass to access the system
    under maintenance for tests purposes!"];
    }

    public function getSection(): string
    {
        return "maintenance mode";
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