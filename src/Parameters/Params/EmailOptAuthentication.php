<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Set the e-mail authentication
 *
 * Class EmailOptAuthentication
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailOptAuthentication extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Set the e-mail authentication!"];
    }

    public function getSectionGroup(): string
    {
        return "e-mail configuration";
    }

    public function getSection(): string
    {
        return "e-mail configuration options";
    }

    public function getValue(): bool
    {
        return $this->value;
    }

    public function getDefaultValue(): bool
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