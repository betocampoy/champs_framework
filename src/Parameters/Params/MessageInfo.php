<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the CSS class of app INFO messages
 *
 * Class MessageClass
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class MessageInfo extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the Information Message CSS class!"];
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
        return "champs_info";
    }

    public function getValidValues(): array
    {
        return [];
    }
}