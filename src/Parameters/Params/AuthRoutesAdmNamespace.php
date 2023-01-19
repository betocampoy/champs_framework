<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthRoutesAdmNamespace extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the namespace to locate the handler!"];
    }

    public function getSection(): string
    {
        return "authentication module - admin routes";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return "/Source/App";
    }

    public function getValidValues(): array
    {
        return [];
    }
}