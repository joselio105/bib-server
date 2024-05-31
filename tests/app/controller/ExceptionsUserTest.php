<?php

use plugse\server\core\infra\http\Request;
use plugse\server\infra\database\mysql\UserModel;
use plugse\server\infra\http\controllers\UsersController;
use plugse\server\core\app\validation\exceptions\IsRequiredError;
use plugse\server\core\app\validation\exceptions\MustBeEmailError;
use plugse\server\core\app\validation\exceptions\MustBePhoneError;
use plugse\server\core\app\validation\exceptions\MustBeStringError;
use plugse\server\core\app\validation\exceptions\MustHaveLengthGreatherThanError;

beforeAll(function () {
    if(!defined('SETTINGS_FILE')){
        define('SETTINGS_FILE', './src/settings/main.php');
    }
});

// afterAll(function () {
//     (new UserModel)->clearTable();
// });

test('Check on null name', function () {
    $request = new Request;
    $controller = new UsersController;
    $controller->create($request);
})->throws(IsRequiredError::class);

test('Check on small name', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'',
        'email'=>'email@server.nrt',
        'phone'=>'(00)11222-3344',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(MustHaveLengthGreatherThanError::class);

test('Check on non string name', function () {
    $request = new Request;
    $request->setBody([
        'name'=>0,
        'email'=>'email@server.nrt',
        'phone'=>'(00)11222-3344',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(MustBeStringError::class);

test('Check on null email', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'Any Name',
        'email'=>null,
        'phone'=>'(00)11222-3344',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(IsRequiredError::class);

test('Check on non string email', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'Any Name',
        'email'=>1,
        'phone'=>'(00)11222-3344',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(MustBeStringError::class);

test('Check on non email email', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'Any Name',
        'email'=>'Any Name',
        'phone'=>'(00)11222-3344',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(MustBeEmailError::class);

test('Check on null phone', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'Any Name',
        'email'=>'email@server.nrt',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(IsRequiredError::class);

test('Check on non string phone', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'Any Name',
        'email'=>'email@server.nrt',
        'phone'=>0,
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(MustBeStringError::class);

test('Check on non phone phone', function () {
    $request = new Request;
    $request->setBody([
        'name'=>'Any Name',
        'email'=>'email@server.nrt',
        'phone'=>'email@server.nrt',
    ]);
    $controller = new UsersController;
    $controller->create($request);
})->throws(MustBePhoneError::class);

