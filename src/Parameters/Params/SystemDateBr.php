<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


/**
 *
 * Define the format of date Brazil
 *
 * Class AuthClassEntity
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class SystemDateBr extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the format of date brazil (Default d/m/Y)!"];
    }

    public function getSection(): string
    {
        return "system";
    }

    public function getValue():string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return "d/m/Y";
    }

    public function getValidValues(): array
    {
        return [];
    }
}