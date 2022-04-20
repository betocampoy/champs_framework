<?php


namespace BetoCampoy\ChampsFramework\Controller\Validator;


use Rakit\Validation\Validation;

interface ValidatorInterface
{

    public function __construct(array $inputs, array $rules = [], array $messages = []);

    public function defaultRules();

}