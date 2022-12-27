<?php


namespace BetoCampoy\ChampsFramework\Support\Validator\Validators;

use BetoCampoy\ChampsFramework\Support\Validator\Validator;

class UserValidator extends Validator
{
    public function __construct(array $inputs, array $rules = [], array $message = [])
    {
        parent::__construct($inputs, $rules, $message);
    }

    public function defaultRules(): array
    {
        return [
          "name" => "required|min:2|max:60",
          "email" => "required|email|unique:User,email",
        ];
    }

    public function defaultAliases(): array
    {
        return [
          "name" => "Nome",
          "email" => "E-mail",
        ];
    }

}