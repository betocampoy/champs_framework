<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the charset of e-mail
 *
 * Class EmailOptCharset
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailOptCharset extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected array $dependencies = [
        SystemEncoding::class
    ];

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the e-mail charset!"];
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
        return CHAMPS_SYSTEM_ENCODING;
    }

    public function getValidValues(): array
    {
        return ['UTF-8' => 'UTF-8'];
    }
}