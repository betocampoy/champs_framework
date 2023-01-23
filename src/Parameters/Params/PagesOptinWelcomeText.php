<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesOptinWelcomeText extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the BODY TEXT of opt-in welcome page!"];
    }

    public function getSectionGroup(): string
    {
        return "pages";
    }

    public function getSection(): string
    {
        return "opt-in welcome page";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "Welcome, we are so happy theat you join us!";
    }

    public function getValidValues(): array
    {
        return [];
    }
}