<?php


if(!function_exists("session")) {
    /**
     * Returns an instance of session
     *
     * @return \BetoCampoy\ChampsSao\Session
     */
    function session(): \BetoCampoy\ChampsSao\Session
    {
        return new \BetoCampoy\ChampsSao\Session();
    }
}
