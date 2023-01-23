<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the period of days to recycle period of file
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class LogRecyclePeriod extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the period of days to recycle period of file!"];
    }

    public function getSectionGroup(): string
    {
        return "log";
    }

    public function getSection(): string
    {
        return "log";
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDefaultValue(): int
    {
        return 1;
    }

    public function getValidValues(): array
    {
        return [];
    }
}