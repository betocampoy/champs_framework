<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Parametrize the address of company
 *
 * Class SiteAddressNumber
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SiteAddressNumber extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the site number address!"];
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
        return '111';
    }

    public function getValidValues(): array
    {
        return [];
    }
}