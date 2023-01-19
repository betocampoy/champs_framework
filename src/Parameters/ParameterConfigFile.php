<?php


namespace BetoCampoy\ChampsFramework\Parameters;


class ParameterConfigFile
{
    protected string $configFile;
    protected string $namespace;

    protected array $data = [];

    public function __construct(string $configFile)
    {
        $this->configFile = $configFile;
        if (!file_exists($this->configFile)) {
            $fp = fopen($this->configFile, 'w');
            $jsonArr = ["CHAMPS_CONFIG_FILE_CREATED_AT" => date('Y-m-d')];
            fwrite($fp, json_encode($jsonArr, JSON_PRETTY_PRINT));   // here it will print the array pretty
            fclose($fp);
        }
        $this->read();
    }

    protected function read(): ParameterConfigFile
    {
        $parameters = file_get_contents($this->configFile);
        $this->data = json_decode($parameters, true);
        return $this;
    }

    /* read the Parameter class files registered to generate the config file */
    public function save(array $parameters): ParameterConfigFile
    {
        $dataNew = array_merge($this->data, $parameters);
        ksort($dataNew);

        $fp = fopen($this->configFile, 'w');
        fwrite($fp, json_encode($dataNew, JSON_PRETTY_PRINT));   // here it will print the array pretty
        fclose($fp);
        $this->read();
        return $this;
    }

    /**
     * @param string|null $parameter
     * @return array|mixed|null
     */
    public function getParameter(?string $parameter = null)
    {
        if (!$parameter) {
            return null;
        }
        return $this->data[$parameter] ?? null;
    }

    public function getData()
    {
        return $this->data;
    }

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