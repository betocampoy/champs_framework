<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthRoutesOprAction extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the handler action (method)!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication - operator routes";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return "home";
    }

    public function getValidValues(): array
    {
        return [];
    }
}