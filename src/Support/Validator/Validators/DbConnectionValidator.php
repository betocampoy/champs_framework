<?php


namespace BetoCampoy\ChampsFramework\Support\Validator\Validators;

use BetoCampoy\ChampsFramework\Support\Validator\Validator;

class DbConnectionValidator extends Validator
{
    public function __construct(array $inputs, array $rules = [], array $message = [])
    {
        parent::__construct($inputs, $rules, $message);
    }

    public function defaultRules(): array
    {
        return [
            "name" => "required",
            "dbname" => "required",
            "dbuser" => "required",
            "dbpass" => "required",
        ];
    }

    public function defaultAliases(): array
    {
        return [
        ];
    }

}