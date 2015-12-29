<?php

namespace Electrode\Navigator\Http\Session;

/**
 * Interface SessionInterface
 * @package Electrode\Navigator\Http\Session
 */
interface SessionInterface
{

    /**
     * @param $key
     * @param $value
     * @return $this
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
     * @return $this
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