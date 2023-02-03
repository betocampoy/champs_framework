<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class MessageTimeoutOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Fade-out the message after sometime. By default is ON!"];
    }

    public function getSection(): string
    {
        return "message";
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

    public function value():bool
    {
        return $this->value == 'on';
    }

    public function validator($value = null):array
    {
        return [
            $this->name => $value == 'on' || $value == true
        ];
    }
}