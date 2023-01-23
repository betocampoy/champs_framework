<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Parametrize the address of company
 *
 * Class SiteAddressCity
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SiteAddressCity extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Inform the city where company is located!"];
    }

    public function getSection(): string
    {
        return "site address";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return 'City';
    }

    public function getValidValues(): array
    {
        return [];
    }
}