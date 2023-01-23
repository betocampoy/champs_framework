<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


/**
 *
 * Define the format of datetime in App
 *
 * Class AuthClassEntity
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */

class SystemDatetimeApp extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the format of datetime app (Default Y-m-d H:i:s)!"];
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
        return "Y-m-d H:i:s";
    }

    public function getValidValues(): array
    {
        return [];
    }
}