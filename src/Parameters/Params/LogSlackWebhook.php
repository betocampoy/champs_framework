<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the Slack Webhook
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class LogSlackWebhook extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the Slack Webhook!"];
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