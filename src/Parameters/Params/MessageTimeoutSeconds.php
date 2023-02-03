<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MessageTimeoutSeconds extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the seconds message will fadeout"];
    }

    public function getSection(): string
    {
        return "message";
    }

    public function getValue():int
    {
        return $this->value;
    }

    public function getDefaultValue():int
    {
        return 3;
    }

    public function getValidValues(): array
    {
        return [];
    }
}