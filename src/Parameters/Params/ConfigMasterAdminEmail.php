<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class ConfigMasterAdminEmail extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "hidden";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter the e-mail of MASTER ADMIN FRAMEWORK. This user is used when auth module is not activated!"];
    }

    public function getSectionGroup(): string
    {
        return "general";
    }

    public function getSection(): string
    {
        return "initial configuration";
    }

    public function getValue():?string
    {
        return $this->value;
    }

    public function getDefaultValue():?string
    {
        return null;
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => filter_var($value, FILTER_VALIDATE_EMAIL)
        ];
    }
}