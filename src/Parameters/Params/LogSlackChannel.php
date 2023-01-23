<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the Slack Channel
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class LogSlackChannel extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the Slack Channel!"];
    }

    public function getSectionGroup(): string
    {
        return "log";
    }

    public function getSection(): string
    {
        return "log slack";
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getDefaultValue(): ?string
    {
        return null;
    }

    public function getValidValues(): array
    {
        return [];
    }
}