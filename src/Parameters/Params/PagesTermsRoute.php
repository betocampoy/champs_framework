<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesTermsRoute extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected array $dependencies = [
        SystemUrlProject::class
    ];

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the route to Agree Terms!"];
    }

    public function getSectionGroup(): string
    {
        return "pages";
    }

    public function getSection(): string
    {
        return "agreed terms page";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return url("/terms");
    }

    public function getValidValues(): array
    {
        return [];
    }
}