<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the log level
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class LogLevel extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define log level!"];
    }

    public function getSectionGroup(): string
    {
        return "log";
    }

    public function getSection(): string
    {
        return "log";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return 'ERROR';
    }

    public function getValidValues(): array
    {
        return [
            'DEBUG',
            'INFO',
            'NOTICE',
            'WARNING',
            'ERROR',
            'CRITICAL',
            'ALERT',
            'EMERGENCY',
        ];
    }
}