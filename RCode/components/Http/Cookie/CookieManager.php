<?php

namespace RCode\Components\Http\Cookie;

/**
 * Class CookieManager
 * @package RCode\Components\Http\Cookie
 */
class CookieManager
{
    /**
     * @var CookieInterface []
     */
    private $cookies = array();

    public function __construct()
    {
        $this->build();
    }

    /**
     * @param $name
     * @param $value
     * @param \DateTime $expired
     */
    public function addCookie($name, $value, \DateTime $expired)
    {
        $cookie = new Cookie($name, $value, $expired);
        $json = array(
            'value' => $value,
            'expired' => $expired->getTimestamp()
        );
        setcookie($name, json_encode($json), $expired->getTimestamp());
        $this->cookies[$cookie->getName()] = $cookie;
    }

    private function build()
    {
        foreach($_COOKIE as $name => $value)
            try {

                $decode = @json_decode($value, 1);
                if (isset($decode['value']) && isset($decode['expired'])) {
                    $cookie = new Cookie($name, $decode['value'], new \DateTime($decode['expired']));
                    $this->cookies[$cookie->getName()] = $cookie;
                }
            } catch(\Exception $ex) {}

    }

    /**
     * @return CookieInterface []
     */
    public function getCookies()
    {
        return array_values($this->cookies);
    }
}