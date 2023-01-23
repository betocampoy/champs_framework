<?php


namespace BetoCampoy\ChampsFramework\Parameters\Params;



class AuthGlobalPermissions extends \BetoCampoy\ChampsFramework\Parameters\Parameter
{
    public function getInputType(): string
    {
        return "text";
    }

    public function getInputAttributes(): array
    {
        return ["help" => "Un associative array to configure permissions where key = action and value = permission 
        (Create an array like this. list=listar;create=inserir;update=atualizar) an the framework will convert it properly!"];
    }

    public function getSectionGroup(): string
    {
        return "authentication";
    }

    public function getSection(): string
    {
        return "authentication general";
    }

    public function getValue():array
    {
        return $this->value;
    }

    public function getDefaultValue():array
    {
        return [];
    }

    public function getValidValues(): array
    {
        return [];
    }

    public function validator($value = null):array
    {
        return [
            $this->name => self::strToArray($value)
        ];
    }
}