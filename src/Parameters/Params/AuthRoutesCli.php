<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthRoutesCli extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the route where client user must be redirect after login!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication - client routes";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return "/client";
    }

    public function getValidValues(): array
    {
        return [];
    }
}