<?php

use plugse\server\core\infra\http\routes\Route;
use plugse\server\infra\http\controllers\UsersController;
use plugse\server\infra\http\midlewares\AuthMidleware;

return [
    new Route('users', 'GET', UsersController::class, 'index'),
    new Route('users/:id', 'GET', UsersController::class, 'show'),
    // new Route('users/query/:query', 'GET', UsersController::class),
    new Route('users/:name/:email', 'GET', UsersController::class, 'index', [AuthMidleware::class]),
];
