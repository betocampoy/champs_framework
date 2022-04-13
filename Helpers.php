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

if(!function_exists("sao_csrf_input")) {
    /**
     * Create an CSRF input to use in http forms. Each time this helper is used, changes the token, so is allowed only one per page
     *
     * @return string
     */
    function sao_csrf_input(): string
    {
        $session = new \BetoCampoy\ChampsSao\Session();
        $session->csrf();
        return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
    }
}

if(!function_exists("sao_csrf_data_attr")) {
    /**
     * Create an CSRF to be used as data-attribute in http inputs. Is allowed use multiple times in page
     *
     * @return string
     */
    function sao_csrf_data_attr(): string
    {
        $session = new \BetoCampoy\ChampsSao\Session();
        return "data-csrf='" . ($session->get_csrf_from_session() ?? "") . "'";
    }
}

if(!function_exists("sao_csrf_verify")) {
    /**
     * Validate if the CSRF token is valide
     *
     * @param $request
     * @return bool
     */
    function sao_csrf_verify($request): bool
    {
        $session = new \BetoCampoy\ChampsSao\Session();
        if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
            return false;
        }
        return true;
    }
}

if(!function_exists("sao_flash")) {
    /**
     * @return null|string
     */
    function sao_flash(): ?string
    {
        $session = new \BetoCampoy\ChampsSao\Session();
        if ($flash = $session->flash()) {
            return $flash;
        }
        return null;
    }
}

if(!function_exists("sao_request_limit")) {
    /**
     * @param string $key
     * @param int $limit
     * @param int $seconds
     * @return bool
     */
    function sao_request_limit(string $key, int $limit = 15, int $seconds = 60): bool
    {
        $session = new \BetoCampoy\ChampsSao\Session();
        if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
            $session->set($key, [
              "time" => time() + $seconds,
              "requests" => $session->$key->requests + 1
            ]);
            return false;
        }

        if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
            return true;
        }

        $session->set($key, [
          "time" => time() + $seconds,
          "requests" => 1
        ]);

        return false;
    }
}

if(!function_exists("sao_request_repeat")) {
    /**
     * @param string $field
     * @param string $value
     * @return bool
     */
    function sao_request_repeat(string $field, string $value): bool
    {
        $session = new \BetoCampoy\ChampsSao\Session();
        if ($session->has($field) && $session->$field == $value) {
            return true;
        }

        $session->set($field, $value);
        return false;
    }
}