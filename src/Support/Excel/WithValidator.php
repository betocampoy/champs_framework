<?php


namespace BetoCampoy\ChampsFramework\Support\Excel;



use BetoCampoy\ChampsFramework\Support\Validator\Validator;

interface WithValidator
{
    public function validator(?array $inputs):Validator;

}