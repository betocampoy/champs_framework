<?php

/**
 ### SESSION HELPERS ###
 */

if(!function_exists("auth")) {
    /**
     * Return the logged user
     *
     * @param $str
     *
     * @return mixed
     */
    function auth()
    {
        return \BetoCampoy\ChampsFramework\Models\Auth\Auth::auth();
    }
}

if(!function_exists("session")) {
    /**
     * Returns an instance of session
     *
     * @return \BetoCampoy\ChampsFramework\Session
     */
    function session(): \BetoCampoy\ChampsFramework\Session
    {
        return new \BetoCampoy\ChampsFramework\Session();
    }
}

if(!function_exists("csrf_input")) {
    /**
     * Create an CSRF input to use in http forms. Each time this helper is used, changes the token, so is allowed only one per page
     *
     * @return string
     */
    function csrf_input(): string
    {
        $session = new \BetoCampoy\ChampsFramework\Session();
        $session->csrf();
        return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
    }
}

if(!function_exists("csrf_data_attr")) {
    /**
     * Create an CSRF to be used as data-attribute in http inputs. Is allowed use multiple times in page
     *
     * @return string
     */
    function csrf_data_attr(): string
    {
        $session = new \BetoCampoy\ChampsFramework\Session();
        return "data-csrf='" . ($session->get_csrf_from_session() ?? "") . "'";
    }
}

if(!function_exists("csrf_verify")) {
    /**
     * Validate if the CSRF token is valide
     *
     * @param $request
     * @return bool
     */
    function csrf_verify($request): bool
    {
        $session = new \BetoCampoy\ChampsFramework\Session();
        if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
            return false;
        }
        return true;
    }
}

if(!function_exists("flash")) {
    /**
     * @return null|string
     */
    function flash(): ?string
    {
        $session = new \BetoCampoy\ChampsFramework\Session();
        if ($flash = $session->flash()) {
            return $flash;
        }
        return null;
    }
}

if(!function_exists("request_limit")) {
    /**
     * @param string $key
     * @param int $limit
     * @param int $seconds
     * @return bool
     */
    function request_limit(string $key, int $limit = 15, int $seconds = 60): bool
    {
        $session = new \BetoCampoy\ChampsFramework\Session();
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

if(!function_exists("request_repeat")) {
    /**
     * @param string $field
     * @param string $value
     * @return bool
     */
    function request_repeat(string $field, string $value): bool
    {
        $session = new \BetoCampoy\ChampsFramework\Session();
        if ($session->has($field) && $session->$field == $value) {
            return true;
        }

        $session->set($field, $value);
        return false;
    }
}

/**
 * ### PASSWORD HELPERS ###
 */


if(!function_exists("is_passwd")) {
    /**
     * Verify if the string is a valide e-mail
     *
     * @param string $password
     * @return bool
     */
    function is_passwd(string $password): bool
    {
        $min = defined('CHAMPS_PASSWD_MIN_LEN') ? CHAMPS_PASSWD_MIN_LEN : 5;
        $max = defined('CHAMPS_PASSWD_MAX_LEN') ? CHAMPS_PASSWD_MAX_LEN : 50;
        if (password_get_info($password)['algo'] || (mb_strlen($password) >= $min && mb_strlen($password) <= $max)) {
            return true;
        }

        return false;
    }
}

if(!function_exists("passwd")) {
    /**
     * @param string $password
     * @return string
     */
    function passwd(string $password): string
    {
        if (!empty(password_get_info($password)['algo'])) {
            return $password;
        }

        return password_hash($password, CHAMPS_PASSWD_ALGO, CHAMPS_PASSWD_OPTION);
    }
}

if(!function_exists("passwd_verify")) {
    /**
     * @param string $password
     * @param string $hash
     * @return bool
     */
    function passwd_verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

if(!function_exists("passwd_rehash")) {
    /**
     * @param string $hash
     * @return bool
     */
    function passwd_rehash(string $hash): bool
    {
        return password_needs_rehash($hash, CHAMPS_PASSWD_ALGO, CHAMPS_PASSWD_OPTION);
    }
}