<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class SecurityForceHttps extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Force the HTTPS in URL!"];
    }

    public function getSection(): string
    {
        return "security";
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function getDefaultValue(): bool
    {
        return false;
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