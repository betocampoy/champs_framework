<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the e-mail host server
 *
 * Class EmailAuthHost
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailAuthHost extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the e-mail host server!"];
    }

    public function getSectionGroup(): string
    {
        return "e-mail configuration";
    }

    public function getSection(): string
    {
        return "e-mail configuration server";
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function getDefaultValue(): ?string
    {
        return null;
    }

    public function getValidValues(): array
    {
        return [];
    }
}