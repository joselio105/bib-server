<?php

use plugse\server\core\app\mappers\Mapper;
use plugse\server\core\app\validation\exceptions\IsRequiredError;
use plugse\server\core\app\validation\exceptions\MustBeEmailError;
use plugse\server\core\app\validation\exceptions\MustBePhoneError;
use plugse\server\core\app\validation\exceptions\MustBeStringError;
use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\Response;
use plugse\server\infra\database\mysql\UserModel;
use plugse\server\infra\http\controllers\UsersController;


beforeAll(function () {
    define('SETTINGS_FILE', './src/settings/main.php');
});

afterAll(function () {
    (new UserModel)->clearTable();
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

test('fail on null name', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>null,
        'email'=>$email,
        'phone'=>$phone,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(IsRequiredError::class);

test('fail on non string name', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>0,
        'email'=>$email,
        'phone'=>$phone,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(MustBeStringError::class);

test('fail on null email', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>null,
        'phone'=>$phone,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(IsRequiredError::class);

test('fail on non string email', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>1,
        'phone'=>$phone,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(MustBeStringError::class);

test('fail on non email email', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>$name,
        'phone'=>$phone,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(MustBeEmailError::class);

test('fail on null phone', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>$email,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(IsRequiredError::class);

test('fail on non string phone', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>$email,
        'phone'=>0,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(MustBeStringError::class);

test('fail on non phone phone', function (string $name, string $email, string $phone) {
    $request = new Request;
    $request->setBody([
        'name'=>$name,
        'email'=>$email,
        'phone'=>$email,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->with('provideUsers')->throws(MustBePhoneError::class);
