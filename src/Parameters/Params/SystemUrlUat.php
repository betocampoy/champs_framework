<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SystemUrlUat extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter the URL of application running in TEST environment!"];
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
        return [];
    }
}