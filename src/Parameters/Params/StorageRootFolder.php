<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class StorageRootFolder extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter folder name of root storage folder. All the other folders will be created there!"];
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
        return "storage";
    }

    public function getValidValues(): array
    {
        return [];
    }
}