<?php

namespace RCode\Components\Injector\Interfaces;

/**
 * Interface DICInterface
 */
interface DICInterface
{
    /**
     * @param $singletonName
     * @param callable $callable
     * @return mixed
     */
    public function setInstance($singletonName, Callable $callable);

    /**
     * @param $instanceName
     * @return mixed
     */
    public function getInstance($instanceName);

    /**
     * @param $instanceName
     * @param callable $callable
     * @return mixed
     */
    public function setSingleton($instanceName, Callable $callable);

    /**
     * @param $singletonName
     * @return mixed
     */
    public function getSingleton($singletonName);
}