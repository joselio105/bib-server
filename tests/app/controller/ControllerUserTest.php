<?php

use plugse\server\core\infra\http\Request;
use plugse\server\core\app\mappers\Mapper;
use plugse\server\core\infra\http\Response;
use plugse\server\infra\http\controllers\UsersController;


beforeAll(function () {
    define('SETTINGS_FILE', './src/settings/main.php');
});

dataset('provideUsers', function () {
    return require 'tests/data/users.php';
});

test('create user', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>$email,
        'phone'=>$phone
    ]);
    $controller = new UsersController;
    $response = $controller->create($request);

    expect($response)->toBeInstanceOf(Response::class);
    expect(http_response_code())->toBe(201);
    expect($response->get())->toBeInstanceOf(Mapper::class);
})->with('provideUsers');

test('find many users', function() {
    $users = require './tests/data/users.php';
    $found = array_filter($users, function($user){
        [$firstName, $middleName, $lastname] = explode(' ', $user['name']);
        return $lastname === 'Barbosa';
    });

    $request = new Request;
    $request->setParams(['query' => 'barbosa']);
    $controller = new UsersController;
    $response = $controller->index($request);

    expect($response)->toBeInstanceOf(Response::class);
    expect(http_response_code())->toBe(200);
    expect($response->get())->toHaveLength(count($found));
    expect($response->get())->each->toHaveKeys(['id', 'name', 'email', 'phone', 'isAdmin', 'isActive']);
});

test('find one user by id', function() {
    $users = require './tests/data/users.php';
    $id = rand(0, count($users)-1);
    $user = $users[$id - 1];

    $request = new Request;
    $request->setParams(['id' => $id]);
    $controller = new UsersController;
    $response = $controller->show($request);

    expect($response)->toBeInstanceOf(Response::class);
    expect(http_response_code())->toBe(200);
    expect($response->get())->toBeInstanceOf(Mapper::class);
    expect($response->get())->toHaveKeys(['id', 'name', 'email', 'phone', 'isAdmin', 'isActive']);
    expect($response->get()->name)->toBe($user['name']);
    expect($response->get()->email)->toBe($user['email']);
    expect($response->get()->phone)->toBe($user['phone']);
});

test('update user', function () {
    $users = require './tests/data/users.php';
    $id = rand(0, count($users)-1);
    $user = $users[$id - 1];
    
    $request = new Request;
    $request->setParams(['id' => $id]);
    $request->setBody([
        'name' =>  'Name Updated'
    ]);
    $controller = new UsersController;
    $response = $controller->update($request);    

    expect($response)->toBeInstanceOf(Response::class);
    expect(http_response_code())->toBe(200);
    expect($response->get())->toBeInstanceOf(Mapper::class);
    expect($response->get())->toHaveKeys(['id', 'name', 'email', 'phone', 'isAdmin', 'isActive']);
    expect($response->get()->name)->not->toBe($user['name']);
    expect($response->get()->email)->toBe($user['email']);
    expect($response->get()->phone)->toBe($user['phone']);
});
