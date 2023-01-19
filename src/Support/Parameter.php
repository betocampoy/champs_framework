<?php


namespace BetoCampoy\ChampsFramework\Support;


class Parameter
{


    public function __call($name, $arguments)
    {
        if(substr($name, 0, 6) !== 'champs') return 'invalid';

        return $arguments[0];
    }

    public function champsEnvironmentIdentifier($value = null):string
    {
        if(!in_array($value, ["DEV", "UAT", "PRD"])) return "invalid";
        return $value;
    }

    public function champsSessionName($value = null):string
    {
        if(!$value) return "invalid";
        return strtoupper(str_slug($value));
    }

    /**
     * CHAMPS_AUTH_REQUIRED_FIELDS
     *
     * @param null $value
     * @return array|string
     */
    public function champsAuthRequiredFields($value = null)
    {
        if(!$value){
            return "invalid";
        }
        return [];
    }

    /**
     * CHAMPS_AUTH_ROUTES_CREATE
     *
     * @param null $value
     * @return array|string
     */
    public function champsAuthRoutesCreate($value = null):bool
    {
        return $value == 'on';
    }

    /**
     * CHAMPS_OPTIN_ROUTES_CREATE
     *
     * @param null $value
     * @return array|string
     */
    public function champsOptinRoutesCreate($value = null)
    {
        if(!$value){
            return "invalid";
        }
        return $value == 'on' ? true : false;
    }
}