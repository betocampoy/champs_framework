<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the CSS class of app ERROR messages
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class MessageError extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the Error Message CSS class!"];
    }

    public function getSection(): string
    {
        return "message";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "champs_danger";
    }

    public function getValidValues(): array
    {
        return [];
    }
}