<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthRoutesAdm extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the route where admin user must be redirect after login!"];
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
        return "/admin";
    }

    public function getValidValues(): array
    {
        return [];
    }
}