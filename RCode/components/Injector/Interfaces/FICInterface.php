<?php

namespace RCode\Components\Injector\Interfaces;

/**
 * Interface FICInterface
 */
interface FICInterface
{
    /**
     * @param DICInterface $DICInterface
     * @return mixed
     */
    public function setDIC(DICInterface $DICInterface);

    /**
     * @return mixed
     */
    public function getDIC();

    /**
     * @param $method
     * @param array $args
     * @param bool|true $singleton
     * @return mixed
     */
    public function execute($method, array $args = array(), $singleton = true);
}