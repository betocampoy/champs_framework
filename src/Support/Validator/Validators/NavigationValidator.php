<?php


namespace BetoCampoy\ChampsFramework\Support\Validator\Validators;

use BetoCampoy\ChampsFramework\Support\Validator\Validator;

class NavigationValidator extends Validator
{
    public function __construct(array $inputs, array $rules = [], array $message = [])
    {
        parent::__construct($inputs, $rules, $message);
    }

    public function defaultRules(): array
    {
        return [
            "theme_name" => "required",
            "display_name" => "required",
            "route" => "required",
        ];
    }

    public function defaultAliases(): array
    {
        return [
        ];
    }

}