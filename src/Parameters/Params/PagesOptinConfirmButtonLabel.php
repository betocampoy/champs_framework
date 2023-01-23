<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesOptinConfirmButtonLabel extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the BUTTON LABEL of opt-in confirmation page!"];
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
        return "Login now!";
    }

    public function getValidValues(): array
    {
        return [];
    }
}