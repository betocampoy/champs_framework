<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesOptinConfirmText extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the BODY TEXT of opt-in confirmation page!"];
    }

    public function getSectionGroup(): string
    {
        return "pages";
    }

    public function getSection(): string
    {
        return "opt-in confirm page";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "We sent a confirmation link in you e-mail. Access and follow the instructions to validated your registration";
    }

    public function getValidValues(): array
    {
        return [];
    }
}