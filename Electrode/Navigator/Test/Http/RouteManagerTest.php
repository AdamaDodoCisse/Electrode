<?php
namespace Electrode\Navigator\Test\Http;

use Electrode\Navigator\Http\Route\Route;
use Electrode\Navigator\Http\Route\RouteManager;
use Electrode\Navigator\Http\Route\RouteNotFoundException;
use Electrode\Navigator\Http\Route\RouterReaderInterface;

require_once __DIR__ . "/../../../../vendor/autoload.php";

class RouteReader implements RouterReaderInterface
{
    public function __construct()
    {
        $this->routes = [
            (new Route('/'))->setName('home'),
            (new Route('/page/:id'))->setName('page')->setPattern('id', '\d+'),
        ];
    }

    /**
     * @return Route []
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param $name
     * @return Route
     */
    public function getRouteByName($name)
    {
        // TODO: Implement getRouteByName() method.
    }
}

class RouteManagerTest extends \PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->manager = new RouteManager(new RouteReader());
    }

    public function testSimpleURL()
    {
        $route = $this->manager->getRouteWhoMatchURL("/");
        $this->assertNotNull($route);
        $this->assertInstanceOf(Route::class, $route);
        $this->assertSame($route->getName(), 'home');
    }

    public function testNotFoundSimpleURL()
    {
        $this->setExpectedException(RouteNotFoundException::class);
        $this->manager->getRouteWhoMatchURL("/not-found");
    }

    public function testParameterURL()
    {
        $route = $this->manager->getRouteWhoMatchURL('/page/1');
        $this->assertInstanceOf(Route::class, $route);
        $this->assertArrayHasKey('id', $route->getParameters());
        $this->assertEquals($route->getParameters()['id'], 1);
        $this->assertSame($route->getName(), 'page');
    }

    public function testNotFoundParameterURL()
    {
        $this->setExpectedException(RouteNotFoundException::class);
        $this->manager->getRouteWhoMatchURL("/page/hello");
    }
}