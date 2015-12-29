<?php

namespace Electrode\Navigator\Http\Route;

/**
 * Interface RouterReaderInterface
 * @package Electrode\Navigator\Http\Route
 */
interface RouterReaderInterface
{
    /**
     * @return Route []
     */
    public function getRoutes();

    /**
     * @param $name
     * @return Route
     */
    public function getRouteByName($name);
}