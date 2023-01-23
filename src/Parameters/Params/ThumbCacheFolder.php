<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define Cropper Cache folder
 *
 * Class PagerLimit
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class ThumbCacheFolder extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define Cropper Cache folder!"];
    }

    public function getSection(): string
    {
        return "thumb images";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "cache";
    }

    public function getValidValues(): array
    {
        return [];
    }
}