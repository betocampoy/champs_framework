<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;

/**
 * Define the secure e-mail protocol
 *
 * Class EmailOptSecure
 * @package BetoCampoy\ChampsFramework\Parameters\Params
 */
class EmailOptSecure extends \BetoCampoy\ChampsFramework\Parameters\Parameter
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
        return ["help" => "Define the secure e-mail protocol!"];
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
        return 'tls';
    }

    public function getValidValues(): array
    {
        return [];
    }
}