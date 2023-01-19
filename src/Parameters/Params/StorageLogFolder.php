<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class StorageLogFolder extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter folder name of LOG files!"];
    }

    public function getSection(): string
    {
        return "storage";
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDefaultValue(): string
    {
        return "log";
    }

    public function getValidValues(): array
    {
        return [];
    }
}