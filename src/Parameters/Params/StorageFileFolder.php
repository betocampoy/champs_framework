<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class StorageFileFolder extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter folder name of DOCUMENT files!"];
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
        return "files";
    }

    public function getValidValues(): array
    {
        return [];
    }
}