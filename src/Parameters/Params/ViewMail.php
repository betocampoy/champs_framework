<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the name of MAIL view theme
 *
 * Class ViewExtension
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class ViewMail extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the name of ADM view theme!"];
    }

    public function getSection(): string
    {
        return "view layer config";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "email";
    }

    public function getValidValues(): array
    {
        return [];
    }
}