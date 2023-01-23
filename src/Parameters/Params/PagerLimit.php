<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the limit of record per page
 *
 * Class PagerLimit
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class PagerLimit extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the limit of records per page in pager module!"];
    }

    public function getSection(): string
    {
        return "pager";
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getDefaultValue(): int
    {
        return 30;
    }

    public function getValidValues(): array
    {
        return [];
    }
}