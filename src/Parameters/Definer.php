<?php


namespace BetoCampoy\ChampsFramework\Parameters;


class Definer
{
    protected string $prefix;
    protected ParameterConfigFile $configFile;
    protected array $parameters = [];
    protected string $namespace;

    /**
     * Definer constructor.
     * @param ParameterConfigFile $configFile
     * @param string $prefix
     */
    public function __construct(ParameterConfigFile $configFile, string $prefix = 'CHAMPS_')
    {
        $this->prefix = strtoupper(($prefix[strlen($prefix)-1] === '_') ? $prefix : "{$prefix}_");
        $this->namespace = (new \ReflectionClass(static::class))->getNamespaceName() . "\Params";
        $this->configFile = $configFile;

        $this->autoRegister();
    }

    /*
     * GETTER
     */
    public function getPrefix():string
    {
        return $this->prefix ?? '';
    }

    public function getConfigFile():ParameterConfigFile
    {
        return $this->configFile;
    }

    protected function registerParameter(string $parameter):Definer
    {
        $className = str_replace('-', '', str_title(str_slug($parameter)));
        $classPath = $this->namespace . "\\" . $className;

        if(!class_exists($classPath)) return $this;

        $idx = array_search($parameter, $this->parameters);
        if($idx !== false) unset($this->parameters[$idx]);
        array_push($this->parameters, $parameter);
        return $this;
    }

    protected function autoRegister():void
    {
        $paramsFolder = dir(__DIR__."/Params");

        while($paramFile = $paramsFolder -> read()){
            if(is_file($paramsFolder->path."/".$paramFile))
                $this->registerParameter(strtoupper(str_snake_case(str_replace(".php", "", $paramFile))));
        }
        $paramsFolder -> close();

    }

    public function getParameters():array
    {
        $arrParams = [];
        foreach ($this->parameters as $parameter){
            $className = str_replace('-', '', str_title(str_slug($parameter)));
            $classPath = $this->namespace . "\\" . $className;
            $arrParams = array_merge($arrParams, (new $classPath($this, $this->prefix))->getParameterAsArray());
        }
        return $arrParams;
    }

    public function getParametersFiltered(?string $section = null):array
    {
        $arrSections = [];
        $arrParamsFiltered = [];
        foreach ($this->parameters as $parameter){
            $className = str_replace('-', '', str_title(str_slug($parameter)));
            $classPath = $this->namespace . "\\" . $className;
            $class = new $classPath($this, $this->prefix);
            $arrParams = $class->getParameterAsArray();

            if(!in_array($arrParams[$class->name]['sectionGroup'], $arrSections)){
                $arrSections[] = $arrParams[$class->name]['sectionGroup'];
            }
            if($section && $arrParams[$class->name]['sectionGroup'] != $section) continue;

            $arrParamsFiltered[$arrParams[$class->name]['section']][$class->name] = $arrParams[$class->name];
        }
        return [
            "sections" => $arrSections,
            "parameters" => $arrParamsFiltered
        ];
    }

    public function getParametersBySection():array
    {
        $arrParamsSec = [];
        foreach ($this->parameters as $parameter){
            $className = str_replace('-', '', str_title(str_slug($parameter)));
            $classPath = $this->namespace . "\\" . $className;
            $class = new $classPath($this, $this->prefix);
            $arrParams = $class->getParameterAsArray();
            $arrParamsSec[$arrParams[$class->name]['section']][$class->name] = $arrParams[$class->name];
        }
        return $arrParamsSec;
    }

    /**/
    public function render():Definer
    {
        $arrParams = [];
        foreach ($this->getParameters() as $constant => $values){
            $arrParams = array_merge($arrParams, [$constant => $values['value']]);
        }

        $this->configFile = $this->configFile->save($arrParams);
        return $this;
    }

    public function save(array $data):Definer
    {
        $sanitData = [];
        foreach ($this->parameters as $parameter){
            echo $parameter . PHP_EOL;
            $className = str_replace('-', '', str_title(str_slug($parameter)));
            $classPath = $this->namespace . "\\" . $className;
            $class = (new $classPath($this, $this->prefix));
            if(!empty($data['section_group']) && $class->getSectionGroup() != $data['section_group']) continue;
            $validated = $class->validator($data[$this->prefix.$parameter] ?? null);
            if(is_array($validated)){
                $sanitData = array_merge($sanitData, $validated);
            }
        }
        $this->configFile = $this->configFile->save($sanitData);
        return $this;
    }

    /**
     * After render all information, this method properly define the constants
     *
     * @return $this
     */
    public function define():Definer
    {
        foreach ($this->getConfigFile()->getData() as $parameter => $value){
            if(!defined($parameter)) define($parameter, $value);
        }
        return $this;
    }

    public function generateDefinesToHelpIDE():string
    {
        $string = "<?php".PHP_EOL;
        foreach ($this->getParameters() as $name => $values){
            $string .= "if (!defined('{$name}')) define('{$name}', '');".PHP_EOL;
        }
        return $string;
    }

//    /* read the Parameter class files registered to generate the config file */
//    public function save(array $parameters):Definer
//    {
//        $dataJson =  file_get_contents($this->configFile);
//        $data = json_decode($dataJson, true);
//        $dataNew = array_merge($data, $parameters);
//        ksort($dataNew);
//
//        $fp = fopen($this->configFile, 'w');
//        fwrite($fp, json_encode($dataNew, JSON_PRETTY_PRINT));   // here it will print the array pretty
//        fclose($fp);
//        return $this;
//    }

//    /* load the config file and define tne environment constants */
//    protected function load():Definer
//    {
//        $parameters = file_get_contents($this->configFile);
//        $parameters_data = json_decode($parameters, true);
//        foreach ($parameters_data as $constant => $value) {
//            if (!defined($constant)) {
//                define($constant, $value);
//            }
//        }
//        return $this;
//    }
}