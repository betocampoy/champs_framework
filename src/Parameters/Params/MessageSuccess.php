<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the CSS class of app SUCCESS messages
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class MessageSuccess extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the Success Message CSS class!"];
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
        return "alert-success";
    }

    public function getValidValues(): array
    {
        return [];
    }
}