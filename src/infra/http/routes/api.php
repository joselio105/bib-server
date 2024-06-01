<?php

use plugse\server\core\infra\http\routes\GroupedRoutes;
use plugse\server\infra\http\controllers\PublicationsController;
use plugse\server\infra\http\controllers\UsersController;
use plugse\server\infra\http\middlewares\AuthMiddleware;

return [
    (new GroupedRoutes())
        ->setPrefix('users')
        ->setController(UsersController::class)
        // ->setMiddleware(AuthMiddleware::class)
        ->addRoute('query/:query', 'GET', 'index')
        ->addRoute(':id', 'GET', 'show')
        ->addRoute('', 'POST', 'create')
        ->addRoute(':id', 'put', 'update')
        ->addRoute(':id', 'delete', 'delete'),
    (new GroupedRoutes())
        ->setPrefix('publications')
        ->setController(PublicationsController::class)        
        ->addRoute('query/:query', 'GET', 'index')
        ->addRoute(':id', 'GET', 'show')
        ->addRoute('', 'POST', 'create', [AuthMiddleware::class])
        ->addRoute(':id', 'put', 'update', [AuthMiddleware::class])
        ->addRoute(':id', 'delete', 'delete', [AuthMiddleware::class]),
];
