<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class ConfigExampleThemeCreate extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define if the example theme must be created in the project!"];
    }

    public function getSection(): string
    {
        return "initial configuration";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():bool
    {
        return true;
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => $value == 'on' || $value == true
        ];
    }

}