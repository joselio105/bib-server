<?php

use plugse\server\core\infra\http\Request;
use plugse\server\infra\http\controllers\UsersController;

require './vendor/autoload.php';

define('SETTINGS_FILE', './src/settings/main.php');
$controller = new UsersController;

$req = new Request;
$req->setBody([
    'name' => 'Name Updated'
]);
$req->setParams([
    'id' => 2
]);

var_dump ($controller->update($req)->get());