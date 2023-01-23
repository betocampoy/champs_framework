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
        return ["help" => "Define a list of IP addresses to by-pass to access the system
    under maintenance for tests purposes! (use ';' to separate values)"];
    }

    public function getSectionGroup(): string
    {
        return "maintenance mode";
    }

    public function getSection(): string
    {
        return "general";
    }

    public function getValue(): ?array
    {
        return $this->value;
    }

    public function getDefaultValue(): ?array
    {
        return null;
    }

    public function getValidValues(): array
    {
        return ["127.0.0.1"];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => self::strToArray($value)
//            $this->name => array_filter(explode(";", $value))
        ];
    }
}