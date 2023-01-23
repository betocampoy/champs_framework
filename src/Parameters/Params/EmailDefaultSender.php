<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the e-mail default sender
 *
 * Class EmailDefaultSender
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailDefaultSender extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the e-mail default sender!"];
    }

    public function getSectionGroup(): string
    {
        return "e-mail configuration";
    }

    public function getSection(): string
    {
        return "e-mail configuration sender";
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function getDefaultValue(): array
    {
        return [];
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => self::strToArray($value)
        ];
    }
}