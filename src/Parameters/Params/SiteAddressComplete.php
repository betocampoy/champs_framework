<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Parametrize the address of company
 *
 * Class SiteAddressCity
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SiteAddressComplete extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected bool $runTimeParameter = true;
    protected array $dependencies = [
        SiteAddressStreet::class,
        SiteAddressNumber::class,
        SiteAddressComplement::class,
        SiteAddressCity::class,
        SiteAddressState::class,
        SiteAddressZipcode::class,
    ];

    public function getInputType(): string
    {
        return "hidden";
    }

    public function getInputAttributes(): array
    {
        return [];
    }

    public function getSection(): string
    {
        return "site address";
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getDefaultValue(): ?string
    {
        return '';
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function value(): ?string
    {
        $street = CHAMPS_SITE_ADDRESS_STREET;
        $number = CHAMPS_SITE_ADDRESS_NUMBER;
        $complement = CHAMPS_SITE_ADDRESS_COMPLEMENT;
        $zipcode = CHAMPS_SITE_ADDRESS_ZIPCODE;
        $city = CHAMPS_SITE_ADDRESS_CITY;
        $state = CHAMPS_SITE_ADDRESS_STATE;

        return "
<div class='site_address'>
<p class='site_address_street'>{$street}, {$number} | {$complement}</p>
<p class='site_address_city'>{$city}/{$state} - {$zipcode}</p>
</div>";
    }
}