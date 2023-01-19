<?php


namespace BetoCampoy\ChampsFramework\Parameters;


use BetoCampoy\ChampsFramework\Parameters\Params\SystemSessionName;

abstract class Parameter implements ParameterContract
{

    protected bool $runTimeParameter = false;

    /**
     * Parameters Definer object
     *
     * @var string
     */
    protected Definer $definer;

    /**
     * The final name of constant as result of concatenate the prefix and name of the class.
     *
     * @var string
     */
    protected string $name;

    /**
     * The current value of the parameter after load config json file
     *
     * @var mixed|null
     */
    protected $value;

//    /*
//     * THE PARAMETERS BELLOW, MUST BE SET AT CHILDREN CLASSES, TO DEFINE THE BEHAVIOR OF PARAMETER
//     */
//
//    /**
//     * The section will help to render the array of inputs of the html manage form
//     *
//     * @var string
//     */
//    protected string $section = "system";
//
//    /**
//     * The input type.
//     *
//     * @var string
//     */
//    protected string $inputType = "text";
//
//    /**
//     * Input attributes
//     *
//     * @var array
//     */
//    protected array $inputAttributes = [];
//
//    /**
//     * Valid values is used with inputType select
//     *
//     * @var array
//     */
//    protected array $validValues = [];
//
//    /**
//     * Is the initial value of parameter
//     *
//     * @var null|mixed
//     */
//    protected $defaultValue = null;
//
    /**
     * Avoid precedence errors loading dependencies before
     *
     * @var array
     */
    protected array $dependencies = [];
//
//    public function __get($name)
//    {
//        return $this->$name;
//    }

    /**
     * Parameter constructor.
     *
     * @param Definer $definer
     */
    public function __construct(Definer $definer)
    {
        $this->definer = $definer;
        $this->name = $this->definer->getPrefix() .
            strtoupper(str_snake_case((new \ReflectionClass(static::class))->getShortName()));

        if (!$this->loadDependencies()) {
            return;
        };

        /*
         * if it's a run-time parameter don't define the constant
         */
        if ($this->runTimeParameter) {
            $this->value = $this->value();
        } else {

            $this->value = defined($this->name)
                ? constant($this->name)
                : ($this->definer->getConfigFile()->getParameter($this->name) ?? $this->value());

        }

    }

    protected function loadDependencies(): bool
    {
        if ($this->dependencies) {

            foreach ($this->dependencies as $dependency) {

                /* check if the dependency is it self to avoid infinity nesting */
                if ($dependency == get_class($this)) {
                    continue;
                }

                if (class_exists($dependency)) {
                    /** @var Parameter $depClass */
                    $depClass = new $dependency($this->definer);
                    call_user_func([$depClass, "define"], []);
                    if(!$depClass->isLoaded()) return false;
                }
            }
        }
        return true;
    }

    protected function isLoaded(): bool
    {
        if(!defined($this->name)) {
            return false;
        }

        if(empty($this->getValidValues())){
            return true;
        }

        return in_array($this->value, $this->getValidValues());
    }

    public function getParameterAsArray():array
    {
        return [
            $this->name => [
                "section" => $this->getSection(),
                "inputType" => $this->getInputType(),
                "inputAttributes" => $this->getInputAttributes(),
                "value" => $this->getValue(),
                "validValues" => $this->getValidValues(),
            ]
        ];
    }

    /**
     * @return mixed|null|string|array
     */
    public function value()
    {
        return $this->getDefaultValue();
    }

//    protected function loadSavedValue(): Parameter
//    {
//        if (!defined($this->name)) {
//            $value = ($this->definer->getConfigFile()->getParameter($this->name))
//                ? $this->definer->getConfigFile()->getParameter($this->name)
//                : null;
//
//            define($this->name, $value);
//        }
//        return $this;
//    }
//
    /**
     * Check if the constant was defined and if not define it
     */
    public function define(): void
    {
        if (!defined($this->name)) define($this->name, $this->value);
    }

    public function validator($value = null):array
    {
        return [
            $this->name => $value
        ];
    }

    
}