<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthFacebookAppId extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the the Facebook application id!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication - oauth facebook";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return "";
    }

    public function getValidValues(): array
    {
        return [];
    }
}