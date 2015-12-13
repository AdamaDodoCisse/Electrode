<?php

namespace RCode\Components\Http\Route;

/**
 * Interface RouterReaderInterface
 * @package RCode\Components\Http\Route
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