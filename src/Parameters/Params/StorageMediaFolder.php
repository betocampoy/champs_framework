<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;


class StorageMediaFolder extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Enter folder name of MEDIA files!"];
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
        return "medias";
    }

    public function getValidValues(): array
    {
        return [];
    }
}