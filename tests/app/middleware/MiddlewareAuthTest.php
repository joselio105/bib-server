<?php

use plugse\server\core\errors\PermitionDeniedError;
use plugse\server\core\errors\PermitionIncorrectError;
use plugse\server\core\errors\TokenDecodeError;
use plugse\server\core\errors\TokenExpiredError;
use plugse\server\core\helpers\Crypto;
use plugse\server\core\helpers\Classname;
use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;
use plugse\server\core\infra\http\routes\Route;
use plugse\server\core\infra\http\routes\Router;
use plugse\test\mocks\controllers\BarController;
use plugse\server\infra\http\middlewares\AuthMiddleware;


test('auth pass', function () {
    if(defined('SETTING_FILE')){
        define('SETTINGS_FILE', './src/settings/main.php');
    }
    $token = Crypto::CreateJWT(2)['token'];
    $request = (new Request)
        ->setUri('bar/123')
        ->setHttpMethod('post')
        ->setHeader([
            'Authorization'=>"Bearer {$token}"
        ]
    );

    $router = new Router(
        $request, 
        [new Route('bar/:id', 'POST', BarController::class, 'index', [AuthMiddleware::class])]
    );
    $route = $router->getRoute();
    $request->setParams($router->getParams($route));

    foreach($route->middwares as $middleware){
        Classname::runMethod($middleware, 'run', [$request]);
    }
    $response = Classname::runMethod(
        $route->controller,
        $route->action,
        [],
        [$request]
    );

    expect($response)->toBeInstanceOf(Response::class);
    expect(http_response_code())->toEqual(200);
});

test('fail permition denied', function () {
    $this->expectException(PermitionDeniedError::class);
    $request = (new Request)
        ->setUri('bar/123')
        ->setHttpMethod('post');

    $router = new Router(
        $request, 
        [new Route('bar/:id', 'POST', BarController::class, 'index', [AuthMiddleware::class])]
    );
    $route = $router->getRoute();
    $request->setParams($router->getParams($route));

    foreach($route->middwares as $middleware){
        Classname::runMethod($middleware, 'run', [$request]);
    }

    Classname::runMethod(
        $route->controller,
        $route->action,
        [],
        [$request]
    );
});

test('fail permition incorrect', function () {
    $this->expectException(PermitionIncorrectError::class);
    $request = (new Request)
        ->setUri('bar/123')
        ->setHttpMethod('post')
        ->setHeader(['Authorization'=>'xxx']);

    $router = new Router(
        $request, 
        [new Route('bar/:id', 'POST', BarController::class, 'index', [AuthMiddleware::class])]
    );
    $route = $router->getRoute();
    $request->setParams($router->getParams($route));

    foreach($route->middwares as $middleware){
        Classname::runMethod($middleware, 'run', [$request]);
    }

    Classname::runMethod(
        $route->controller,
        $route->action,
        [],
        [$request]
    );
});

test('fail token decode', function () {
    $this->expectException(TokenDecodeError::class);
    $request = (new Request)
        ->setUri('bar/123')
        ->setHttpMethod('post')
        ->setHeader(['Authorization'=>"Bearer a.b.c"]);

    $router = new Router(
        $request, 
        [new Route('bar/:id', 'POST', BarController::class, 'index', [AuthMiddleware::class])]
    );
    $route = $router->getRoute();
    $request->setParams($router->getParams($route));

    foreach($route->middwares as $middleware){
        Classname::runMethod($middleware, 'run', [$request]);
    }

    Classname::runMethod(
        $route->controller,
        $route->action,
        [],
        [$request]
    );
});

test('fail token expired', function () {
    $this->expectException(TokenExpiredError::class);
    $token = Crypto::CreateJWT(2, Crypto::getTimestamp('2024-01-01'))['token'];
    $request = (new Request)
        ->setUri('bar/123')
        ->setHttpMethod('post')
        ->setHeader(['Authorization'=>"Bearer {$token}"]);

    $router = new Router(
        $request, 
        [new Route('bar/:id', 'POST', BarController::class, 'index', [AuthMiddleware::class])]
    );
    $route = $router->getRoute();
    $request->setParams($router->getParams($route));

    foreach($route->middwares as $middleware){
        Classname::runMethod($middleware, 'run', [$request]);
    }

    Classname::runMethod(
        $route->controller,
        $route->action,
        [],
        [$request]
    );
});
