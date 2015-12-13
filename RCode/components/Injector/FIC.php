<?php

namespace RCode\Components\Injector;

use RCode\Components\Injector\Interfaces\DICInterface;
use RCode\Components\Injector\Interfaces\FICInterface;

/**
 * Class FIC
 * @package RCode\Components\Injector
 */
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
     * @param array $parameters
     * @param array $args
     * @param bool $singleton
     * @return array
     * @throws \Exception
     */
    public function getReflectionValues(array $parameters = array(), array $args = array(), $singleton = true) {
        $values = [];
        /** @var \ReflectionParameter $parameter */
        foreach($parameters as $parameter) {
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
    public function getReflectionValue(\ReflectionParameter $reflectionParameter, array $args = array(), $singleton = true) {
        if(isset($args[$reflectionParameter->getName()])) {
            return $args[$reflectionParameter->getName()];
        } elseif($reflectionParameter->isOptional()) {
            return $reflectionParameter->getDefaultValue();
        } else {
            if(!is_null($reflectionParameter->getClass())) {
                $className = $reflectionParameter->getClass()->getName();
                if($singleton === true) {
                    return $this->dic->getSingleton($className);
                } else {
                    return $this->dic->getInstance($className);
                }
            }
        }
        throw new \Exception('Impossible to resolve <code>'. $reflectionParameter->getName() . '</code>');
    }

    /**
     * @param $method
     * @param array $args
     * @param bool $singleton
     * @return mixed
     * @throws \Exception
     */
    public function execute($method , array $args = array(), $singleton = true) {
        if(is_array($method) && count($method) >= 2) {
            list($class , $name) = $method;
            $reflection = new \ReflectionMethod($class , $name);
        } elseif(is_callable($method)) {
            $reflection = new \ReflectionFunction($method);
        } else {
            throw new \Exception();
        }

        $parameters = $reflection->getParameters();
        $args = $this->getReflectionValues($parameters, $args, $singleton);
        if($reflection instanceof \ReflectionFunction) {
            return $reflection->invokeArgs($args);
        } else {
            return $reflection->invokeArgs($class, $args);
        }
    }

    /**
     * @param DICInterface $dic
     * @return mixed|void
     */
    public function setDIC(DICInterface $dic)
    {
        $this->dic = $dic;
    }

    /**
     * @return DIC
     */
    public function getDIC()
    {
        return $this->dic;
    }
}