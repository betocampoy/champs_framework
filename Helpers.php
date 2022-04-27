<?php


/**
 ### VALIDATION ###
 */

if(!function_exists("is_admin")) {

    /**
     * Verify if the logged user is admin
     *
     * @return bool
     */
    function is_admin(): bool
    {
        return (int)user()->access_level_id === (int)1;
    }

}

if(!function_exists("is_operator")) {
    /**
     * Verify if the logged user is an operador
     *
     * @return bool
     */
    function is_operator(): bool
    {
        return (int)user()->access_level_id === (int)2;
    }
}

if(!function_exists("is_client")) {
    /**
     * Verify if the logged user is a client
     *
     * @return bool
     */
    function is_client(): bool
    {
        return (int)user()->access_level_id === (int)3;
    }
}

if(!function_exists("is_email")) {
    /**
     * Verify if is an e-mail
     *
     * @param string $email
     * @return bool
     */
    function is_email(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}


/**
 ### SESSION HELPERS ###
 */

if(!function_exists("user")) {
    /**
     * Return the logged user
     *
     * @param $str
     *
     * @return mixed
     */
    function user()
    {
        if(defined('CHAMPS_AUTH_MODEL') && !empty(CHAMPS_AUTH_MODEL)){
            $callable = (CHAMPS_AUTH_MODEL."::user")();
            if(is_callable($callable)){
                return call_user_func($callable);
            }
        }
        return \BetoCampoy\ChampsFramework\Models\Auth\User::user();
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
### STRINGS HELPERS ###
 */

if(!function_exists("str_slug")) {
    /**
     * Converts a string in a slug. Replacing all special caracters
     *
     * @param string $string
     *
     * @return string
     */
    function str_slug(string $string): string
    {
        $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
        $formats
          = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
        $replace
          = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

        $slug = str_replace(["-----", "----", "---", "--"], "-",
          str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
          )
        );
        return $slug;
    }
}

if(!function_exists("str_studly_case")) {
    /**
     * Converts a string in a StudlyCase caps
     *
     * @param string $string
     *
     * @return string
     */
    function str_studly_case(string $string): string
    {
        $string = str_slug($string);
        $studlyCase = str_replace(" ", "",
          mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
        );

        return $studlyCase;
    }
}

if(!function_exists("str_upper_case")) {
    /**
     * Converts a string in a UPPER caps
     *
     * @param string $string
     *
     * @return string
     */
    function str_upper_case(
      string $string,
      string $encoding = CONF_SYS_ENCODING
    ): string {
        return mb_strtoupper($string, $encoding);
    }
}

if(!function_exists("str_lower_case")) {
    /**
     * Converts a string in a lower caps
     *
     * @param string $string
     *
     * @return string
     */
    function str_lower_case(
      string $string,
      string $encoding = CONF_SYS_ENCODING
    ): string {
        return mb_strtolower($string, $encoding);
    }
}

if(!function_exists("str_camel_case")) {
    /**
     * Converts a string in a camelCase caps
     *
     * @param string $string
     *
     * @return string
     */
    function str_camel_case(string $string): string
    {
        return lcfirst(str_studly_case($string));
    }
}

if(!function_exists("str_title")) {
    /**
     * Converts a string in a Tittle caps
     *
     * @param string $string
     *
     * @return string
     */
    function str_title(string $string): string
    {
        return mb_convert_case(filter_var($string,
          FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
    }
}

if(!function_exists("str_textarea")) {
    /**
     * Prepare the text to be shown in a textarea input
     *
     * @param string $text
     *
     * @return string
     */
    function str_textarea(string $text): string
    {
        $text = filter_var($text, FILTER_SANITIZE_STRIPPED);
        $arrayReplace = [
          "&#10;",
          "&#10;&#10;",
          "&#10;&#10;&#10;",
          "&#10;&#10;&#10;&#10;",
          "&#10;&#10;&#10;&#10;&#10;"
        ];
        return "<p>" . str_replace($arrayReplace, "</p><p>", $text) . "</p>";
    }
}

if(!function_exists("str_limit_words")) {
    /**
     * Prepare the string to be shown in the page, reducing the amount of displayed words
     * very useful of posts
     *
     * @param string $string
     * @param int    $limit
     * @param string $pointer
     *
     * @return string
     */
    function str_limit_words(string $string, int $limit, string $pointer = "..." ): string
    {
        $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
        $arrWords = explode(" ", $string);
        $numWords = count($arrWords);

        if ($numWords < $limit) {
            return $string;
        }

        $words = implode(" ", array_slice($arrWords, 0, $limit));
        return "{$words}{$pointer}";
    }
}

if(!function_exists("str_limit_chars")) {
    /**
     * Prepare the string to be shown in the page, reducing the amount of displayed characters
     * very useful of posts
     *
     * @param string $string
     * @param int    $limit
     * @param string $pointer
     *
     * @return string
     */
    function str_limit_chars(
      string $string,
      int $limit,
      string $pointer = "..."
    ): string {
        $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
        if (mb_strlen($string) <= $limit) {
            return $string;
        }

        $chars = mb_substr($string, 0,
          mb_strrpos(mb_substr($string, 0, $limit), " "));
        return "{$chars}{$pointer}";
    }
}

if(!function_exists("str_money")) {
    /**
     * Returns a formated money number
     *
     * @param string $price
     *
     * @return string
     */
    function str_money(?string $price): string
    {
        return number_format((!empty($price) ? $price : 0), 2, ",", ".");
    }
}

if(!function_exists("str_number_app")) {
    /**
     * Returns a formated number number
     *
     * @param string|null $value
     * @param int|null    $decimals
     *
     * @return string
     */
    function str_number_app(?string $value): string
    {
        return str_replace([".", ","],["","."], $value);
    }
}

if(!function_exists("str_number_fmt_br")) {
    /**
     * Returns a formated number number
     *
     * @param string|null $value
     * @param int|null    $decimals
     *
     * @return string
     */
    function str_number_fmt_br(?string $value, ?int $decimals = 0): string
    {
        return number_format((!empty($value) ? $value : 0), $decimals, ",", ".");
    }
}

if(!function_exists("str_search")) {
    /**
     * Prepare the string before do a search in database
     *
     * @param string|null $search
     *
     * @return string
     */
    function str_search(?string $search): string
    {
        if (!$search) {
            return "all";
        }

        $search = preg_replace("/[^a-z0-9A-Z\@\ ]/", "", $search);
        return (!empty($search) ? $search : "all");
    }
}

if(!function_exists("str_remove_diacritic")) {
    /**
     * Sanitize the string, removing all diacritic
     *
     * @param $string
     *
     * @return string
     */
    function str_remove_diacritic($string): string
    {
        return preg_replace([
          "/(á|à|ã|â|ä)/",
          "/(Á|À|Ã|Â|Ä)/",
          "/(é|è|ê|ë)/",
          "/(É|È|Ê|Ë)/",
          "/(í|ì|î|ï)/",
          "/(Í|Ì|Î|Ï)/",
          "/(ó|ò|õ|ô|ö)/",
          "/(Ó|Ò|Õ|Ô|Ö)/",
          "/(ú|ù|û|ü)/",
          "/(Ú|Ù|Û|Ü)/",
          "/(ñ)/",
          "/(Ñ)/",
          "/(ç)/",
          "/(Ç)/"
        ], explode(" ", "a A e E i I o O u U n N c C"), $string);
    }
}

if(!function_exists("str_only_numbers")) {
    /**
     * Sanitize the string, removing all non numeric characters
     *
     * @param string $string
     *
     * @return string
     */
    function str_only_numbers(string $string): string
    {
        return preg_replace("/[^0-9]/", '', $string);
    }
}

if(!function_exists("str_fix_spaces")) {
    /**
     * Replaces successive space characters (note, not just spaces, but also line breaks or tabs) with a single, conventional space (' ').
     * \s+ says "match a sequence, made up of one or more space characters".
     *
     * @param string $string
     *
     * @return string
     */
    function str_fix_spaces(string $string): string
    {
        //\s is a shorthand for [\t\r\n\f]. It matches new line, return and form feed as well as tabulator
        return preg_replace('/\s+/', ' ', trim($string));
    }
}


/**
### PERMISSIONS HELPERS ###
 */

if(!function_exists("hasPermission")) {

    /**
     * Verify if the logged user has a specific permission or and array of permission and return true or false
     * In case of an array of permissions, by default the user must have all permissions to returns true. if only one permission
     * is enought, change the parameter $allOrNothing to false.
     *
     * @param string|array $permissions
     * @param bool         $all
     *
     * @return bool
     */
    function hasPermission($permissions, bool $allOrNothing = true): bool
    {
        if (!user()) {
            return false;
        }

        if (!is_array($permissions)) {
            return user()->hasPermission($permissions);
        }

        foreach ($permissions as $permission) {
            $hasAccess = user()->hasPermission($permission);

            if ($allOrNothing && !$hasAccess) {
                return false;
            }

            if (!$allOrNothing && $hasAccess) {
                return true;
            }

        }
        return $hasAccess;
    }
}

if(!function_exists("hasPermissionRedirectIfFail")) {

    /**
     * Redirect the access to forbidden route if hasPemission returns false
     *
     * @param string|array $permissions
     * @param bool         $all
     */
    function hasPermissionRedirectIfFail($permissions, bool $all = true): void
    {
        if (!hasPermission($permissions, $all)) {
            $forbidden = (defined("CHAMPS_DEFAULT_ROUTES") && isset(CHAMPS_DEFAULT_ROUTES['forbidden']))
              ? CHAMPS_DEFAULT_ROUTES['forbidden']
              : "ops/forbidden" ;
            redirect($forbidden);
        }
    }
}


/**
### URL HELPERS ###
 */

if(!function_exists("current_url")) {
    /**
     * Returns the current url, even if it's a route or an querystring
     *
     * @return bool
     */
    function current_url():string
    {
        if (isset($_GET['route'])){
            return (filter_input(INPUT_GET, "route", FILTER_SANITIZE_STRIPPED) ?? "/");
        }

        return $_SERVER['PHP_SELF'];
    }
}

if(!function_exists("url")) {
    /**
     * Prepare the url based on current environment
     *
     * @param string $path
     * @return string
     */
    function url(string $path = null): string
    {
        if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
            $urlProject = (defined('CHAMPS_URL_TEST') ? CHAMPS_URL_TEST : "");
        }else{
            $urlProject = (defined('CHAMPS_URL') ? CHAMPS_URL : "");
        }

        $urlBase = $urlProject[strlen($urlProject)-1] == "/" ? substr($urlProject, 0, strlen($urlProject)-1) : $urlProject;
        if ($path) {
            $path = $path[strlen($path)-1] == "/" ? substr($path, 0, strlen($path)-1) : $path;
            return $urlBase . "/" . ($path[0] == "/"
                ? mb_substr($path, 1)
                : $path);
        }
        return $urlBase ? $urlBase : "";

    }
}

if(!function_exists("url_back")) {
    /**
     * @return string
     */
    function url_back(): string
    {
        return ($_SERVER['HTTP_REFERER'] ?? url());
    }
}

if(!function_exists("theme")) {
    /**
     * Prepare the url based on a theme
     *
     * @param string|null $path
     * @param string $theme
     * @return string
     */
    function theme(string $path = null, string $theme = CHAMPS_VIEW_WEB): string
    {
        if ($path) {
            $path = $path[strlen($path)-1] == "/" ? substr($path, 0, strlen($path)-1) : $path;
            return url() . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return url() . "/themes/{$theme}";
    }
}

if(!function_exists("image")) {
    /**
     * Access to images using package Thumb()
     *
     * @param string $image
     * @param int $width
     * @param int|null $height
     * @return string
     */
    function image(?string $image, int $width, int $height = null): ?string
    {
        if ($image) {
            return url() . "/" . (new \Source\Support\Thumb())->make($image, $width, $height);
        }

        return null;
    }
}

if(!function_exists("isXmlHttpRequest")){
    /**
     * Identify if it is a ajax request
     * @return bool
     */
    function isXmlHttpRequest():bool
    {
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : null;
        return (strtolower($isAjax) === 'xmlhttprequest');
    }
}

if(!function_exists("redirect")) {
    /**
     * Execute navigation redirection
     *
     * @param string $url
     */
    function redirect(string $url): void
    {

        header("HTTP/1.1 302 Redirect");
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            header("Location: {$url}");
            exit;
        }

        if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
            $location = url($url);
            header("Location: {$location}");
            exit;
        }
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