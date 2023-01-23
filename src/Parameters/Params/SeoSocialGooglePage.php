<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * This parameter is used by SEO PACKAGE
 *
 * Class SeoSocialGooglePage
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class SeoSocialGooglePage extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Inform the Google page!"];
    }

    public function getSectionGroup(): string
    {
        return "seo configuration";
    }

    public function getSection(): string
    {
        return "social medias";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return '';
    }

    public function getValidValues(): array
    {
        return [];
    }
}