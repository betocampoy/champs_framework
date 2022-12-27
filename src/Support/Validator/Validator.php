<?php


namespace BetoCampoy\ChampsFramework\Support\Validator;


use BetoCampoy\ChampsFramework\Support\Validator\CustomRules\FilteredDataByAuthUserRule;
use BetoCampoy\ChampsFramework\Support\Validator\CustomRules\UniqueRule;
use Rakit\Validation\Validation;
use Rakit\Validation\Validator as VendorValidator;


/**
 * Class Validator
 *
 * This class is developed by Beto Campoy
 * Its purposed is abstract the used of package Rakit Validator (link of manual: https://github.com/rakit/validation)
 * Basicly this package is used to easly validate input forms, based in Laravel Framework
 *
 * How to implement: Create a new class in the same namespace, and extends validator class, after that, implements only
 * two methods in the extended class, the constructor and the default rules, the construct must only call
 * the parent methos passing the parameters, and de defaultRules method, must return an array with
 * the rules.
 *
 * How to use: Example of usage in controller
 *
 * $replaceRules = ["inputName" => "required|numeric];
 * $validator = new ModelValidator($inputsForm, $replaceRules);
 * $validation = $validator->validate();
 *
 * if ($validation->fails()) {
 *     // handler the error
 * }
 *
 * @package Source\Support\Validator
 */
abstract class Validator implements ValidatorInterface
{

    /** @var array */
    protected $inputs;

    /** @var array */
    protected $rules;

    /** @var array */
    protected $aliases;

    /** @var array */
    protected $custom_messages;

    /** @var \Rakit\Validation\Validator */
    protected VendorValidator $validator;

    /**
     * Validator constructor.
     *
     * @param array $inputs
     * @param array $rules
     * @param array $aliases
     * @param array $messages
     *
     * @throws \Rakit\Validation\RuleQuashException
     */
    public function __construct(array $inputs, array $rules = [], array $aliases = [], array $messages = [])
    {
        $this->inputs = $inputs;
        $this->rules = $this->rules($rules);
        $this->aliases = $this->aliases($aliases);
        $this->custom_messages = $messages ?? $this->messages();

        $this->validator = new VendorValidator();
        $this->validator->addValidator('unique', new UniqueRule());
        $this->validator->addValidator('user_data', new FilteredDataByAuthUserRule());

        $this->changeLanguageMessages(CHAMPS_FRAMEWORK_LANG);
    }

    /**
     * This method is responsible to call de vendor method responsible to validate data
     *
     * @return \Rakit\Validation\Validation
     */
    public function validate():Validation
    {
        $validation = $this->validator->make($this->inputs, $this->rules);
        $validation->setAliases($this->aliases);
        $validation->validate($this->inputs);
        return $validation;
    }

    /**
     * This method is responsible to simplify and call de vendor method responsible to make a validation
     * Use the defaultAliases method in child class to change de attribute name in the messages
     *
     * @return \Rakit\Validation\Validation
     */
    public function make():Validation
    {
        $validation = $this->validator->make($this->inputs, $this->rules);
        $validation->setAliases($this->aliases);
        return $validation;
    }

    /**
     * This methos must return an array with the rules to validate
     *
     * @return array
     */
    public function defaultRules(): array
    {
        return [];
    }

    /**
     * This methos must return an array with the aliases from attributes
     *
     * @return array
     */
    public function defaultAliases(): array
    {
        return [];
    }

    /**
     * @param array $rules
     *
     * @return array
     */
    public function rules(array $rules = []) :array
    {
        foreach ($rules as $key => $value){
            if (isset($this->defaultRules()[$key])){
                unset($this->defaultRules()[$key]);
            }
        }
        $mergedRules = array_merge($this->defaultRules(), $rules);

        return $mergedRules;
    }

    /**
     * @param array $aliases
     *
     * @return array
     */
    public function aliases(array $aliases = []) :array
    {
        foreach ($aliases as $key => $value){
            if (isset($this->defaultAliases()[$key])){
                unset($this->defaultAliases()[$key]);
            }
        }
        $mergedAliases = array_merge($this->defaultAliases(), $aliases);

        return $mergedAliases;
    }

    /**
     * @return array
     */
    public function messages() :array
    {
        return [];
    }

    /**
     * @param string $language
     */
    public function changeLanguageMessages(string $language = 'pt-br'):void
    {
        $messages = [];
        $translates = [];
        $language = strtolower($language);
        if($language == 'pt-br'){
            if(file_exists(__DIR__."/ValidationMessages/{$language}.php")){
                include_once __DIR__."/ValidationMessages/{$language}.php";
            }
        }

        $this->validator->setMessages($messages);
        $this->validator->setTranslations($translates);
    }

    /**
     * @param \Rakit\Validation\Validation $validation
     *
     * @return array|null
     */
    public function errors(Validation $validation):?array
    {
        if ($validation->fails()) {
            return $validation->errors()->all();
        }
        return null;
    }

}