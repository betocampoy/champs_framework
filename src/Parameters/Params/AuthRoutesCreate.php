<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthRoutesCreate extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define if the default login, logout, forget password routes must be created!"];
    }

    public function getSection(): string
    {
        return "authentication module";
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
}