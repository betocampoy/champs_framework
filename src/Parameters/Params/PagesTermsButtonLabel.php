<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesTermsButtonLabel extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the Button Label of Agree Terms!"];
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
        return "Agreed terms";
    }

    public function getValidValues(): array
    {
        return [];
    }
}