<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Parametrize the address of company
 *
 * Class SiteAddressComplement
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SiteAddressComplement extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the site complement address!"];
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
        return '1st floor';
    }

    public function getValidValues(): array
    {
        return [];
    }
}