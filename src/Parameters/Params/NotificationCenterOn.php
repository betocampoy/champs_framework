<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class NotificationCenterOn extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{

    public function getInputType(): string
    {
        return "switch";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Activate or Deactivate the Notification Center!"];
    }

    public function getSectionGroup(): string
    {
        return "notification center";
    }

    public function getSection(): string
    {
        return "notification center";
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

    public function value():bool
    {
        return $this->value == 'on';
    }

    public function validator($value = null):array
    {
        return [
            $this->name => $value == 'on' || $value == true
        ];
    }
}