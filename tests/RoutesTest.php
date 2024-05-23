<?php

namespace plugse\test;

use PHPUnit\Framework\TestCase;
use plugse\server\core\helpers\File;
use plugse\server\core\infra\http\Request;
use plugse\test\controllers\BarController;
use plugse\test\controllers\FooController;
use plugse\test\middlewares\TestMiddleware;
use plugse\test\controllers\ErrorControllers;
use PHPUnit\Framework\Attributes\DataProvider;
use plugse\server\core\infra\http\routes\Route;
use plugse\server\core\errors\FileNotFoundError;
use plugse\server\core\infra\http\routes\Router;
use plugse\server\core\errors\ClassNotFoundError;
use plugse\server\core\errors\RouteNotFoundError;
use plugse\server\core\errors\RouteInconcistenceError;
use plugse\server\core\infra\http\routes\GroupeRoutes;

class RoutesTest extends TestCase
{
    public static function provideRoutes(): array
    {
        $routes = [
            new Route('bar/:id', 'get', BarController::class, 'index', [TestMiddleware::class]),
            (new GroupeRoutes())->setPrefix('foo')
                ->setController(FooController::class)
                ->addRoute('', 'GET', 'index')
                ->addRoute(':id', 'GET', 'show')
                ->addRoute('', 'POST', 'create')
                ->addRoute(':id', 'PUT', 'update')
                ->addRoute(':id', 'delete', 'delete')
        ]; 

        return [
            [(new Request)->setUri('bar/123')->setHttpMethod('get'), $routes, ['id'=>'123'], []],
            [(new Request)->setUri('foo')->setHttpMethod('get'), $routes, [], []],
            [(new Request)->setUri('foo/123')->setHttpMethod('get'), $routes, ['id'=>'123'], []],
            [(new Request)->setUri('foo')->setHttpMethod('post'), $routes, [], ['name'=>'Test']],
            [(new Request)->setUri('foo/123')->setHttpMethod('put'), $routes, ['id'=>'123'], ['name'=>'Test']],
            [(new Request)->setUri('foo/123')->setHttpMethod('delete'), $routes, ['id'=>'123'], []],
        ];
    }

    #[DataProvider('provideRoutes')]
    public function testRoute(Request $request, array $routes, array $params, array $body)
    {
        $router = new Router($request, $routes);
        $route = $router->getRoute();

        $this->assertInstanceOf(Route::class, $route);
        $this->assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys(
            $params, 
            $router->getParams($route), 
            array_keys($params)
        );
    }

    public function testInconsistentRoute()
    {
        $this->expectException(RouteInconcistenceError::class);
        $routes = [
            (new GroupeRoutes)
            ->setPrefix('foo')
            ->setController(FooController::class)
            ->addRoute(':id', 'put', 'index')
            ->addRoute(':name', 'put', 'index')
        ];

        (new Router(
            (new Request)->setUri('foo/123')->setHttpMethod('put'), 
            $routes)
        )->getRoute();
    }

    public function testRouteNotFound()
    {
        $this->expectException(RouteNotFoundError::class);
        $routes = [
            (new GroupeRoutes)
            ->setPrefix('foo')
            ->setController(FooController::class)
            ->addRoute(':id', 'put', 'index')
            ->addRoute(':name', 'put', 'index')
        ];

        (new Router(
            (new Request)->setUri('bar/123')->setHttpMethod('put'), 
            $routes)
        )->getRoute();
    }
}
