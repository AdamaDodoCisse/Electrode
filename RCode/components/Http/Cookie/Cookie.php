<?php

namespace RCode\Components\Http\Cookie;

/**
 * Class Cookie
 * @package RCode\Components\Http\Cookie
 */
class Cookie implements CookieInterface
{

    /**
     * @var
     */
    private $name;
    /**
     * @var
     */
    private $value;
    /**
     * @var \DateTime
     */
    private $expired;

    /**
     * @param $name
     * @param $value
     * @param \DateTime $expired
     */
    public function __construct($name, $value, \DateTime $expired) {
        $this->set($name, $value, $expired);
    }

    /**
     * @param $name
     * @param $value
     * @param \DateTime $expired
     */
    public function set($name, $value, \DateTime $expired)
    {
        $this->name = (string) $name;
        $this->setValue($value);
        $this->setExpiredDate($expired);
    }

    /**
     * @param \DateTime $expired
     */
    public function setExpiredDate(\DateTime $expired)
    {
        $this->expired = $expired;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredDate()
    {
        return $this->expired;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->getExpiredDate()->getTimestamp() < (new \DateTime())->getTimestamp();
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}