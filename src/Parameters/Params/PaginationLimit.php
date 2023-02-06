<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the limit of record per page
 *
 * Class PagerLimit
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class PaginationLimit extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the limit of records per page in pagination module!"];
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
        return 20;
    }

    public function getValidValues(): array
    {
        return [];
    }
}