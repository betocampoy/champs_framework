<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class NavbarSaveSession extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Save navbar in session!"];
    }

    public function getSection(): string
    {
        return "navbar module";
    }

    public function getValue():bool
    {
        return $this->value;
    }

    public function getDefaultValue():bool
    {
        return false;
    }

    public function getValidValues(): array
    {
        return [];
    }
}