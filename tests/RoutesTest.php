<?php

namespace plugse\test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use plugse\server\core\errors\ClassNotFoundError;
use plugse\server\core\errors\FileNotFoundError;
use plugse\server\core\errors\RouteInconcistenceError;
use plugse\server\core\errors\RouteNotFoundError;
use plugse\server\core\helpers\File;
use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\routes\Router;
use plugse\server\core\infra\http\routes\GroupeRoutes;
use plugse\server\core\infra\http\routes\Route;
use plugse\test\controllers\BarController;
use plugse\test\controllers\ErrorControllers;
use plugse\test\controllers\FooController;
use plugse\test\middlewares\TestMiddleware;

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
            ['bar/123', 'GET', $routes, ['id'=>'123'], []],
            ['foo', 'GET', $routes, [], []],
            ['foo/123', 'GET', $routes, ['id'=>'123'], []],
            ['foo', 'POST', $routes, [], ['name'=>'Test']],
            ['foo/123', 'PUT', $routes, ['id'=>'123'], ['name'=>'Test']],
            ['foo/123', 'delete', $routes, ['id'=>'123'], []],
        ];
    }

    #[DataProvider('provideRoutes')]
    public function testRoute($uri, $method, $routes, $params, $body)
    {
        $router = new Router($uri, $method, $routes, $body);
        $request = $router->getRequest();

        $this->assertInstanceOf(Request::class, $request);
        $this->assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys($params, $request->params, array_keys($params));
        $this->assertArrayIsIdenticalToArrayOnlyConsideringListOfKeys($body, $request->body, array_keys($body));
        $this->assertEquals($uri, $request->uri);
        $this->assertInstanceOf(Route::class, $request->route);
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

        (new Router('foo/xyz', 'put', $routes))->getRequest();
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

        (new Router('bar/xyz', 'put', $routes))->getRequest();
    }
    
    public function testFileNotFound()
    {
        $this->expectException(FileNotFoundError::class);
        $routes = [
            new Route('foo/:id', 'put', 'plugse\test\\ctrl\\Controller.php', 'index'),
        ];

        $request = (new Router('foo/xyz', 'put', $routes))->getRequest();
        File::runClass($request->route->controller);
    }

    public function testClassNotFound()
    {
        $this->expectException(ClassNotFoundError::class);
        $routes = [
            new Route('foo/:id', 'put', ErrorControllers::class, 'index'),
        ];

        $request = (new Router('foo/xyz', 'put', $routes))->getRequest();
        File::runClass($request->route->controller);
    }
}
