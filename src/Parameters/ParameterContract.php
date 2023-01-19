<?php


namespace BetoCampoy\ChampsFramework\Parameters;


interface ParameterContract
{

    public function getInputType():string;

    public function getInputAttributes():array;

    public function getSection():string;

    public function getValue();

    public function getDefaultValue();

    public function getValidValues():array;

}