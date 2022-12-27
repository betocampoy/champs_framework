<?php


namespace BetoCampoy\ChampsFramework\Support\Validator\Validators;

use BetoCampoy\ChampsFramework\Support\Validator\Validator;

class AuthValidator extends Validator
{
    public function __construct(array $inputs, array $rules = [], array $message = [])
    {
        parent::__construct($inputs, $rules, $message);
    }

    public function defaultRules(): array
    {
        return [
        ];
    }

    public function defaultAliases(): array
    {
        return [
        ];
    }

}