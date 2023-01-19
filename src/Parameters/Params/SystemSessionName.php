<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SystemSessionName extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define an unique session name for application and avoid unlike session sharing!"];
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