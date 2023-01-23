<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


/**
 * Active the SLACK logging
 *
 * Class LogSlackOn
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class LogSlackOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Active the log in slack!"];
    }

    public function getSectionGroup(): string
    {
        return "log";
    }

    public function getSection(): string
    {
        return "log slack";
    }

    public function getValue():bool
    {
        return $this->value;
    }

    public function getDefaultValue():bool
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