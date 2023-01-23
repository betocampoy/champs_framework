<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the language og e-mail module
 *
 * Class EmailOptLanguage
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailOptLanguage extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected array $dependencies = [
        SystemLanguage::class
    ];

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the language of e-mail!"];
    }

    public function getSectionGroup(): string
    {
        return "e-mail configuration";
    }

    public function getSection(): string
    {
        return "e-mail configuration options";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return CHAMPS_SYSTEM_LANGUAGE;
    }

    public function getValidValues(): array
    {
        return [];
    }
}