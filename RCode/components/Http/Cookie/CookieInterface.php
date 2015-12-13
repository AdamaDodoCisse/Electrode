<?php
namespace RCode\Components\Http\Cookie;

/**
 * Interface CookieInterface
 * @package RCode\Components\Http\Cookie
 */
interface CookieInterface
{
    /**
     * @param $name
     * @param $value
     * @param \DateTime $expired
     * @return mixed
     */
    public function set($name, $value, \DateTime $expired);

    /**
     * @param \DateTime $expired
     * @return mixed
     */
    public function setExpiredDate(\DateTime $expired);

    /**
     * @param $value
     * @return mixed
     */
    public function setValue($value);

    /**
     * @return mixed
     */
    public function getExpiredDate();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getValue();

    /**
     * @return mixed
     */
    public function isExpired();
}