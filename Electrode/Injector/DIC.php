<?php

namespace Electrode\Injector;

use Electrode\Injector\Interfaces\DICInterface;

/**
 * Class DIC
 * @package Electrode\Injector
 */
class DIC implements DICInterface
{
    /**
     * @var array
     */
    private $instance = [];
    /**
     * @var array
     */
    private $singletonInstance = [];

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getSingleton($name)
    {
        if (isset($this->singletonInstance[$name])) {
            if (is_null($this->singletonInstance[$name]['value'])) {
                $this->singletonInstance[$name]['value'] = $this->singletonInstance[$name]['callback']();
            }
            return $this->singletonInstance[$name]['value'];
        } else {

            $instance = $this->getInstance($name);
            $this->setSingleton($name, function () use ($instance) {
                return $instance;
            });
            return $this->getSingleton($name);
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getInstance($name)
    {
        if (!is_string($name)) {
            throw new \Exception('Name must be a string');
        }
        $name = rtrim($name, '\\');

        if (isset($this->instance[$name])) {
            return $this->instance[$name]();
        } else {
            $reflectionClass = new \ReflectionClass($name);
            if (!$reflectionClass->isInstantiable()) {
                throw new \Exception('Class ' . $name . ' is not instantiable');
            }
            $constructor = $reflectionClass->getConstructor();

            if (!is_null($constructor)) {
                $parameters = $constructor->getParameters();
                $params = $this->getReflectionParametersValues($parameters);
            } else {
                $params = array();
            }

            $this->setInstance($name, function () use ($reflectionClass, $params) {
                return $reflectionClass->newInstanceArgs($params);
            });

            return $this->getInstance($name);
        }
    }

    /**
     * @param $name
     * @param callable $callable
     * @return mixed|void
     */
    public function setInstance($name, Callable $callable)
    {
        $this->instance[rtrim($name, '\\')] = $callable;
    }

    /**
     * @param array $parameters
     * @return array
     */
    protected function getReflectionParametersValues(array $parameters)
    {
        $params = array();
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            try {
                $parameterName = $parameter->getName();
                $params[$parameterName] = $this->getReflectionParameterValue($parameter);
            } catch (\Exception $exception) {
            }
        }
        return $params;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Exception
     */
    protected function getReflectionParameterValue(\ReflectionParameter $parameter)
    {

        if ($parameter->isOptional()) {
            return $parameter->getDefaultValue();
        } else {
            $parameterClass = $parameter->getClass();
            if (is_null($parameterClass)) {
                throw new \Exception();
            } else {
                return $this->getInstance($parameterClass->getName());
            }
        }
    }

    /**
     * @param $name
     * @param callable $callable
     * @return mixed|void
     */
    public function setSingleton($name, Callable $callable)
    {
        $this->singletonInstance[$name] = array(
            'value' => null,
            'callback' => $callable
        );
    }
}