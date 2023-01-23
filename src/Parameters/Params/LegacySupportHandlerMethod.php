<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



use BetoCampoy\ChampsFramework\Controller\LegacyController;

class LegacySupportHandlerMethod extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Define the handler method who is manage the legacy request!"];
    }

    public function getSectionGroup(): string
    {
        return "legacy support";
    }

    public function getSection(): string
    {
        return "legacy support";
    }

    public function getValue():?string
    {
        return $this->value;
    }

    public function getDefaultValue():string
    {
        return 'home';
    }

    public function getValidValues(): array
    {
        return [];
    }
}