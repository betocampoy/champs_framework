<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the extension of view files
 *
 * Class ViewExtension
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class ViewExtension extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the extension of view files (default .php)!"];
    }

    public function getSection(): string
    {
        return "view layer config";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "php";
    }

    public function getValidValues(): array
    {
        return [];
    }
}