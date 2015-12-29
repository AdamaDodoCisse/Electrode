<?php

namespace Electrode\Injector;

use Electrode\Injector\Interfaces\DICInterface;
use Electrode\Injector\Interfaces\FICInterface;

class FIC implements FICInterface {

    /**
     * @var DIC
     */
    private $dic;

    /**
     * FIC constructor.
     * @param DICInterface $dic
     */
    public function __construct(DICInterface $dic) {
        $this->setDIC($dic);
    }

    /**
     * @param $method
     * @param array $args
     * @param bool $singleton
     * @return mixed
     * @throws \Exception
     */
    public function execute($method, array $args = array(), $singleton = true)
    {
        if (is_array($method) && count($method) >= 2) {
            list($class, $name) = $method;
            $reflection = new \ReflectionMethod($class, $name);
        } elseif (is_callable($method)) {
            $reflection = new \ReflectionFunction($method);
        } else {
            throw new \RuntimeException();
        }

        $parameters = $reflection->getParameters();
        $args = $this->getReflectionValues($parameters, $args, $singleton);
        if ($reflection instanceof \ReflectionFunction) {
            return $reflection->invokeArgs($args);
        } else {
            return $reflection->invokeArgs($class, $args);
        }
    }

    /**
     * @param array $parameters
     * @param array $args
     * @param bool $singleton
     * @return array
     * @throws \Exception
     */
    public function getReflectionValues(array $parameters = array(), array $args = array(), $singleton = true)
    {
        $values = [];
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            $values[] = $this->getReflectionValue($parameter, $args, $singleton);
        }

        return $values;
    }

    /**
     * @param \ReflectionParameter $reflectionParameter
     * @param array $args
     * @param bool $singleton
     * @return mixed
     * @throws \Exception
     */
    public function getReflectionValue(\ReflectionParameter $reflectionParameter, array $args = array(), $singleton = true)
    {
        if (isset($args[$reflectionParameter->getName()])) {
            return $args[$reflectionParameter->getName()];
        } elseif ($reflectionParameter->isOptional()) {
            return $reflectionParameter->getDefaultValue();
        } else {
            if (!is_null($reflectionParameter->getClass())) {
                $className = $reflectionParameter->getClass()->getName();
                if ($singleton === true) {
                    return $this->dic->getSingleton($className);
                } else {
                    return $this->dic->getInstance($className);
                }
            } else {
                if ($singleton === true) {
                    return $this->dic->getSingleton($reflectionParameter->getName());
                } else {
                    return $this->dic->getInstance($reflectionParameter->getName());
                }
            }
        }
    }

    /**
     * @return DIC
     */
    public function getDIC()
    {
        return $this->dic;
    }

    /**
     * @param DICInterface $dic
     * @return mixed|void
     */
    public function setDIC(DICInterface $dic)
    {
        $this->dic = $dic;
    }
}