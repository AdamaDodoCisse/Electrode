<?php

namespace RCode\Components\Http\Route;

/**
 * Class Route
 * @package RCode\Components\Http\Route
 */
class Route
{
    /**
     * @var
     */
    private $name;

    /**
     * @var array
     */
    private $patterns = array();

    /**
     * @var
     */
    private $url;

    /**
     * @var
     */
    private $parameters = array();

    /**
     * @var
     */
    private $security;

    /**
     * @var
     */
    private $action;

    /**
     * Route constructor.
     * @param $url
     */
    public function __construct($url)
    {
        $this->setURL($url);
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     */
    public function getName()
    {
        return $this->name;
    }

    public function hasName()
    {
        return !is_null($this->name);
    }

    /**
     * @param array $parameters
     * @return mixed
     * @throws \Exception
     */
    public function getURL(array $parameters = array())
    {
        $url = $this->url;
        foreach ($this->getParameters() as $key => $regex) {
            if (!isset($parameters[$key])) {
                throw new \Exception("Parameter $key is not found");
            }
            if (!preg_match("#^" . $regex . "$#", $parameters[$key])) {
                throw new \Exception($parameters[$key] . " not match (" . $regex . ')');
            }
            $url = str_replace(":$key", $parameters[$key], $url);
        }
        return $url;
    }

    /**
     * @param $parameter
     * @param $constraint
     * @return $this
     */
    public function setPattern($parameter, $constraint)
    {
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
     * @return $this
     */
    public function setURL($url)
    {
        preg_match_all('#:([\w\d]+[\w\d_]*[\w\d]+)#', $url, $patterns);
        $this->patterns = array();
        foreach ($patterns[1] as $pattern) {
            $this->patterns[$pattern] = ".+";
        }
        $this->url = $url;
        return $this;
    }

    public function getPatterns()
    {
        return $this->patterns;
    }

    /**
     * @param $url
     * @return bool|mixed
     */
    public function accept($url)
    {
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
     * @param callable $action
     * @return $this
     */
    public function setAction(Callable $action)
    {
        $this->action = $action;
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
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}