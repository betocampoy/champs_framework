<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Parametrize the address of company
 *
 * Class SiteAddressStreet
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SiteAddressStreet extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the site street address!"];
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
        return 'Company Street';
    }

    public function getValidValues(): array
    {
        return [];
    }
}