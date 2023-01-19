<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthRoutesCliHandler extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the handler name!"];
    }

    public function getSection(): string
    {
        return "authentication module - client routes";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return "WebExample";
    }

    public function getValidValues(): array
    {
        return [];
    }
}