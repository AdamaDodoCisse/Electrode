<?php

namespace Electrode\Navigator\Http\Route;

/**
 * Class Route
 * @package Electrode\Navigator\Http\Route
 */
class Route
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var string []
     */
    private $patterns = array();

    /**
     * @var string
     */
    private $url;

    /**
     * @var string []
     */
    private $parameters = array();

    /**
     * @var Callable
     */
    private $security;

    /**
     * @var Callable
     */
    private $action;

    /**
     * Route constructor.
     * @param string $url
     */
    public function __construct($url)
    {
        $this->setURL($url);
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        if (!(is_null($name) || is_string($name)))
            throw new \InvalidArgumentException();

        $this->name = $name;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasName()
    {
        return !is_null($this->name);
    }

    /**
     * @param array $parameters
     * @return string
     */
    public function getURL(array $parameters = array())
    {
        $url = $this->url;
        foreach ($this->getPatterns() as $key => $regex) {
            if (!isset($parameters[$key])) {
                throw new \InvalidArgumentException("Parameter $key is not found");
            }

            if (!is_scalar($parameters[$key]))
                throw new \InvalidArgumentException();

            if (!preg_match("#^" . $regex . "$#", $parameters[$key])) {
                throw new \InvalidArgumentException($parameters[$key] . " not match (" . $regex . ')');
            }

            $url = str_replace(":$key", $parameters[$key], $url);
        }
        return $url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setURL($url)
    {
        if (!is_string($url)) {
            throw new \InvalidArgumentException();
        }

        preg_match_all('#:([\w\d]+[\w\d_]*[\w\d]+)#', $url, $patterns);
        $this->patterns = array();
        foreach ($patterns[1] as $pattern) {
            $this->patterns[$pattern] = ".+";
        }

        $this->url = $url;
        return $this;
    }

    /**
     * @return string []
     */
    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @param $parameter
     * @param $constraint
     * @return $this
     */
    public function setPattern($parameter, $constraint)
    {
        if (!(is_string($parameter) && is_string($constraint)))
            throw new \InvalidArgumentException();

        if (isset($this->patterns[$parameter])) {
            $this->patterns[$parameter] = (string)$constraint;
        }

        return $this;
    }

    /**
     * @param $parameter
     * @return null
     */
    public function getPattern($parameter)
    {
        if (isset($this->patterns[$parameter])) {
            return $this->patterns[$parameter];
        } else {
            return null;
        }
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    public function accept($url)
    {
        if (!is_string($url)) {
            throw new \InvalidArgumentException();
        }
        $uri = $this->url;
        foreach ($this->getPatterns() as $key => $regex) {
            $uri = str_replace(":$key", "(?P<$key>$regex)", $uri);
        }
        if (!@preg_match_all("#^$uri$#", $url, $matches)) {
            return false;
        }
        if (!is_callable($this->security)) {
            $this->security = function () {
                return true;
            };
        }
        foreach ($matches as $key => $match) {
            if (is_string($key)) {
                $this->parameters[$key] = $match[0];
            }
        }
        return call_user_func_array($this->security, [$this]);
    }

    /**
     * @param callable $callable
     * @return $this
     */
    public function security(Callable $callable)
    {
        $this->security = $callable;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param callable $action
     * @return $this
     */
    public function setAction(Callable $action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string []
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param $name
     * @return null|string
     */
    public function getParameter($name)
    {
        return isset($this->getParameters()[$name]) ? $this->getParameters()[$name] : null;
    }
}