<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SystemEncoding extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "select";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the system encoding!"];
    }

    public function getSection(): string
    {
        return "system";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return 'UTF-8';
    }

    public function getValidValues(): array
    {
        return ['UTF-8' => 'UTF-8'];
    }
}