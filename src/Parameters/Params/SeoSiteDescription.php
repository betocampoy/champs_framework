<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * This parameter is used by SEO PACKAGE
 *
 * Class SeoSiteDescription
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SeoSiteDescription extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the site description!"];
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
        return 'Site Description';
    }

    public function getValidValues(): array
    {
        return [];
    }
}