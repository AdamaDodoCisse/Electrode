<?php

namespace Electrode\Navigator\Http\Request;

/**
 * Interface RequestInterface
 * @package Electrode\Navigator\Http\Request
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
     * @return array
     */
    public function allPOST();

    /**
     * @return array
     */
    public function allGET();

    /**
     * @param $key
     * @return mixed
     */
    public function valueOfGET($key);

    /**
     * @param $key
     * @return mixed
     */
    public function valueOfPOST($key);

    /**
     * @param $url
     * @param int $status
     * @return mixed
     */
    public function redirect($url, $status = 0);
}