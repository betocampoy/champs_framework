<?php


namespace BetoCampoy\ChampsFramework\Support\Validator\Validators;

use BetoCampoy\ChampsFramework\Support\Validator\Validator;

class DbAliasValidator extends Validator
{
    public function __construct(array $inputs, array $rules = [], array $message = [])
    {
        parent::__construct($inputs, $rules, $message);
    }

    public function defaultRules(): array
    {
        return [
            "environment" => "required|in:DEV,UAT,PRD",
            "alias" => "required",
            "connection" => "required",
        ];
    }

    public function defaultAliases(): array
    {
        return [
        ];
    }

}