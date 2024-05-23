<?php

namespace plugse\test\app;

use PHPUnit\Framework\TestCase;
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

class MiddlewareAuthTest extends TestCase
{
    public function testAuthPass()
    {        
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
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, http_response_code());
    
    }

    public function testFailPermitionDenied()
    {        
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
    }

    public function testFailPermitionIncorrect()
    {        
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
    }

    public function testFailTokenDecode()
    {        
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
    }

    public function testFailTokenExpired()
    {        
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
    }
}
