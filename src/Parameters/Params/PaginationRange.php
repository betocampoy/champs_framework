<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the range of pages
 *
 * Class PagerLimit
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class PaginationRange extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the range of pages for navigation will be shown in pagination module!"];
    }

    public function getSection(): string
    {
        return "pagination";
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDefaultValue(): int
    {
        return 2;
    }

    public function getValidValues(): array
    {
        return [];
    }
}