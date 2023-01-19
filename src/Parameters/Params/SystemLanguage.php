<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SystemLanguage extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "select";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the framework language!"];
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
        return 'en';
    }

    public function getValidValues(): array
    {
        return ['English' => 'en', 'Portugues Brasil' => 'pt-br'];
    }
}