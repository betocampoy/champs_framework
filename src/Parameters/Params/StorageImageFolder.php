<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class StorageImageFolder extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter folder name of IMAGE files!"];
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
        return "images";
    }

    public function getValidValues(): array
    {
        return [];
    }
}