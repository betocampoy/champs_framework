<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define Cropper Image Quality
 *
 * Class PagerLimit
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class ThumbImageQuality extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define Cropper Image Quality (Inform an assoc array like this. jpg=75;png=5) An the framework will convert
        properly!"];
    }

    public function getSection(): string
    {
        return "thumb images";
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function getDefaultValue(): array
    {
        return ["jpg" => 75, "png" => 5];
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => self::strToArray($value)
        ];
    }
}