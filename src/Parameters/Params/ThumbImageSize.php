<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define Cropper Image Size
 *
 * Class PagerLimit
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class ThumbImageSize extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define Cropper Image Size!"];
    }

    public function getSection(): string
    {
        return "thumb images";
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDefaultValue(): int
    {
        return 2000;
    }

    public function getValidValues(): array
    {
        return [];
    }
}