<?php

namespace BetoCampoy\ChampsFramework;


/**
 * Class Session
 *
 * @package BetoCampoy\ChampsFramework
 */
class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        if (!session_id()) {
            session_name(CHAMPS_SYSTEM_SESSION_NAME);
            session_start();
        }
    }

    /**
     * @param $name
     * @return null|mixed
     */
    public function __get($name)
    {
        if (!empty($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return $this->has($name);
    }

    /**
     * @return null|object
     */
    public function all()
    {
        return (object)$_SESSION;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Session
     */
    public function set(string $key, $value): Session
    {
        $_SESSION[$key] = (is_array($value) ? (object)$value : $value);
        return $this;
    }

    /**
     * @param string $key
     * @return Session
     */
    public function unset(string $key): Session
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @return Session
     */
    public function regenerate(): Session
    {
        session_regenerate_id(true);
        return $this;
    }

    /**
     * @return Session
     */
    public function destroy(): Session
    {
        session_destroy();
        return $this;
    }

    /**
     * @return null|mixed|object
     */
    public function flash()
    {
        if ($this->has("flash")) {
            $flash = $this->flash;
            $this->unset("flash");
            return $flash;
        }
        return null;
    }

    /**
     * CSRF Token
     */
    public function csrf(): void
    {
        $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
    }

    /**
     * @return string
     */
    public function get_csrf_from_session(): string
    {
        if (!$this->has('csrf_token')){
            $_SESSION['csrf_token'] = md5(uniqid(rand(), true));
        }
        return $_SESSION['csrf_token'];
    }
}