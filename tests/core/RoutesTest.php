<?php

use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\routes\Route;
use plugse\server\core\infra\http\routes\Router;
use plugse\test\mocks\controllers\BarController;
use plugse\test\mocks\controllers\FooController;
use plugse\server\core\errors\RouteNotFoundError;
use plugse\test\mocks\middlewares\TestMiddleware;
use plugse\server\core\errors\RouteInconcistenceError;
use plugse\server\core\infra\http\routes\GroupedRoutes;


dataset('provideRoutes', function () {
    $routes = [
        new Route('bar/:id', 'get', BarController::class, 'index', [TestMiddleware::class]),
        (new GroupedRoutes())->setPrefix('foo')
            ->setController(FooController::class)
            ->addRoute('', 'GET', 'index')
            ->addRoute(':id', 'GET', 'show')
            ->addRoute('', 'POST', 'create')
            ->addRoute(':id', 'PUT', 'update')
            ->addRoute(':id', 'delete', 'delete')
    ];

    return [
        [(new Request)->setUri('bar/123')->setHttpMethod('get'), $routes, ['id'=>'123']],
        [(new Request)->setUri('foo')->setHttpMethod('get'), $routes, []],
        [(new Request)->setUri('foo/123')->setHttpMethod('get'), $routes, ['id'=>'123']],
        [(new Request)->setUri('foo')->setHttpMethod('post'), $routes, []],
        [(new Request)->setUri('foo/123')->setHttpMethod('put'), $routes, ['id'=>'123']],
        [(new Request)->setUri('foo/123')->setHttpMethod('delete'), $routes, ['id'=>'123']],
    ];
});

test('route', function (Request $request, array $routes, array $params) {
    $router = new Router($request, $routes);
    $route = $router->getRoute();

    expect($route)->toBeInstanceOf(Route::class);
    expect($router->getParams($route))->toBe($params);
})->with('provideRoutes');

test('inconsistent route', function () {
    $this->expectException(RouteInconcistenceError::class);
    $routes = [
        (new GroupedRoutes)
        ->setPrefix('foo')
        ->setController(FooController::class)
        ->addRoute(':id', 'put', 'index')
        ->addRoute(':name', 'put', 'index')
    ];

    (new Router(
        (new Request)->setUri('foo/123')->setHttpMethod('put'), 
        $routes)
    )->getRoute();
});

test('route not found', function () {
    $this->expectException(RouteNotFoundError::class);
    $routes = [
        (new GroupedRoutes)
        ->setPrefix('foo')
        ->setController(FooController::class)
        ->addRoute(':id', 'put', 'index')
        ->addRoute(':name', 'put', 'index')
    ];

    (new Router(
        (new Request)->setUri('bar/123')->setHttpMethod('put'), 
        $routes)
    )->getRoute();
});
