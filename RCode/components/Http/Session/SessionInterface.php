<?php

namespace RCode\Components\Http\Session;

/**
 * Interface SessionInterface
 * @package RCode\Components\Http\Session
 */
interface SessionInterface
{

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function set($key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    public function setFlash($key, $value);

    /**
     * @param $key
     * @return mixed
     */
    public function getFlash($key);

    /**
     * @param $key
     * @return mixed
     */
    public function hasKey($key);

    /**
     * @param $key
     * @return mixed
     */
    public function remove($key);
}