<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class PagesOptinWelcomeImage extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    protected array $dependencies = [
        SystemUrlProject::class,
        ViewWeb::class,
        ViewAdm::class,
        ViewOpr::class,
        ViewApp::class
    ];

    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the IMAGE of opt-in welcome page!"];
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
        return theme("/assets/images/optin_welcome.jpg");
    }

    public function getValidValues(): array
    {
        return [];
    }
}