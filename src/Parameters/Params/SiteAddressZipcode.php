<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Parametrize the address of company
 *
 * Class SiteAddressZipcode
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SiteAddressZipcode extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Inform the address zipcode!"];
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
        return '00000-000';
    }

    public function getValidValues(): array
    {
        return [];
    }
}