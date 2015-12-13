<?php

namespace RCode\Components\Http\Session;

/**
 * Class Session
 * @package RCode\Components\Http
 */
/**
 * Class Session
 * @package RCode\Components\Http\Session
 */
class Session implements SessionInterface
{

    /**
     * @var string
     */
    private $flashName;

    /**
     * Session constructor.
     * @param string $flashName
     */
    public function __construct($flashName = "flash")
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $this->flashName = $flashName;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param $key
     * @return null
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setFlash($key, $value)
    {
        $_SESSION[$this->flashName][$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getFlash($key)
    {
        if (isset($_SESSION[$this->flashName][$key])) {
            return $_SESSION[$this->flashName][$key];
        } else {
            return null;
        }
    }

    /**
     * @param $key
     * @return mixed
     */
    public function remove($key)
    {
        if ($this->hasKey($key)) {
            unset($_SESSION[$key]);
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key)
    {
        return !is_null($this->get($key));
    }
}