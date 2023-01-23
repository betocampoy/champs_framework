<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesOptinWelcomeTitle extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the TITLE of opt-in welcome page!"];
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
        return "Congrats, your registration was validated :)";
    }

    public function getValidValues(): array
    {
        return [];
    }
}