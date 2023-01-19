<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthOptinRoutesCreate extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define if the default opt-in routes must be created!"];
    }

    public function getSection(): string
    {
        return "authentication module";
    }

    public function getValue():bool
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