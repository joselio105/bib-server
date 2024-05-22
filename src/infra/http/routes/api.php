<?php

use plugse\server\core\infra\http\routes\GroupeRoutes;
use plugse\server\core\infra\http\routes\Route;
use plugse\server\infra\http\controllers\PublicationsController;
use plugse\server\infra\http\controllers\UsersController;
use plugse\server\infra\http\middlewares\AuthMiddleware;

return [
    (new GroupeRoutes())
        ->setPrefix('users')
        ->setController(UsersController::class)
        ->setMiddleware(AuthMiddleware::class)
        ->addRoute('query/:query', 'GET', 'index')
        ->addRoute(':id', 'GET', 'show')
        ->addRoute('', 'POST', 'create')
        ->addRoute(':id', 'put', 'update')
        ->addRoute(':id', 'delete', 'delete'),

    new Route('publications', 'GET', PublicationsController::class, 'index'),
];
