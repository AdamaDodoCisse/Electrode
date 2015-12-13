<?php

namespace RCode\Components\Http\Route;

/**
 * Class RouteManager
 * @package RCode\Components\Http\Route
 */
class RouteManager
{
    /**
     * @var RouterReaderInterface
     */
    private $readerInterface;

    /**
     * @param RouterReaderInterface $readerInterface
     */
    public function __construct(RouterReaderInterface $readerInterface)
    {
        $this->readerInterface = $readerInterface;
    }

    /**
     * @param $url
     * @return Route
     * @throws \Exception
     */
    public function getRouteWhoMatchURL($url)
    {
        foreach ($this->readerInterface->getRoutes() as $route) {
            if ($route->accept($url)) {
                return $route;
            }
        }
        throw new RouteNotFoundException();
    }
}