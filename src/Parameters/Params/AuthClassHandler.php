<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthClassHandler extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Create a custom authentication handler!"];
    }

    public function getSection(): string
    {
        return "authentication module";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return \BetoCampoy\ChampsFramework\Controller\AuthController::class;
    }

    public function getValidValues(): array
    {
        return [];
    }
}