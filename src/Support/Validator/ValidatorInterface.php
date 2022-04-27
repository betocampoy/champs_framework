<?php


namespace BetoCampoy\ChampsFramework\Support\Validator;


use Rakit\Validation\Validation;

interface ValidatorInterface
{

    public function __construct(array $inputs, array $rules = [], array $messages = []);

    public function defaultRules();

}