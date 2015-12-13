<?php

namespace RCode\Components\Http\Request;

/**
 * Interface RequestInterface
 * @package RCode\Components\Http\Request
 */
interface RequestInterface
{

    /**
     * @return mixed
     */
    public function getURL();

    /**
     * @param $url
     * @return mixed
     */
    public function setURL($url);

    /**
     * @param $method
     * @return mixed
     */
    public function setMethod($method);

    /**
     * @return mixed
     */
    public function getMethod();

    /**
     * @param $method
     * @return mixed
     */
    public function isMethod($method);

    /**
     * @return mixed
     */
    public function isPOST();

    /**
     * @return mixed
     */
    public function isGET();

    /**
     * @return mixed
     */
    public function isPUT();

    /**
     * @return mixed
     */
    public function isDELETE();

    /**
     * @return mixed
     */
    public function isAJAX();

    /**
     * @param $url
     * @return mixed
     */
    public function redirect($url);
}