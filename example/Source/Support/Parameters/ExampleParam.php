<?php


class ExampleParam extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Example Parameter"];
    }

    public function getSection(): string
    {
        return "Teste";
    }

    public function getValue():string
    {
        return "Teste Teste";
    }

    public function getDefaultValue():string
    {
        return "It's working";
    }

    public function getValidValues(): array
    {
        return [];
    }
}