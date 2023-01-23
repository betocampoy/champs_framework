<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Set the e-mail html messages
 *
 * Class EmailOptHtml
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailOptHtml extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Set the e-mail html messages!"];
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