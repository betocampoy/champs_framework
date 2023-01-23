<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * This parameter is used by SEO PACKAGE
 *
 * Class SeoSiteLanguage
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SeoSiteLanguage extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected array $dependencies = [
        SystemLanguage::class
    ];

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the site language!"];
    }

    public function getSectionGroup(): string
    {
        return "seo configuration";
    }

    public function getSection(): string
    {
        return "generals";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return CHAMPS_SYSTEM_LANGUAGE;
    }

    public function getValidValues(): array
    {
        return [];
    }
}