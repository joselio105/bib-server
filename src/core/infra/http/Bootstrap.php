<?php

namespace plugse\server\core\infra\http;

use plugse\server\core\helpers\File;
use plugse\server\core\helpers\Classname;
use plugse\server\core\infra\http\routes\Route;
use plugse\server\core\infra\http\routes\Router;

class Bootstrap
{
    private Router $router;
    private Request $request;
    private Route $route;

    public function __construct(Request $request = null)
    {
        date_default_timezone_set('America/Sao_Paulo');
        define('SETTINGS_FILE', './src/settings/main.php');
        $this->request = $request ?? new Request;

        $this->router = new Router(
            $this->request,
            File::getFileData('src/infra/http/routes/api.php')
        );
        $this->corsPolicy();
    }

    public function run()
    {
        try {
            $this->route = $this->router->getRoute();
            $this->request->setParams($this->router->getParams($this->route));
            $this->runMidlewares();
            $response = $this->runAction();

            echo json_encode($response->get());
        } catch (\Throwable $th) {
            echo json_encode(['error' => $th->getMessage()], JSON_PRETTY_PRINT);
        }
    }

    private function runAction()
    {
        return Classname::runMethod(
            $this->route->controller,
            $this->route->action,
            [],
            [$this->request]
        );
    }

    private function runMidlewares()
    {
        foreach ($this->route->middwares as $middleware) {
            Classname::runMethod($middleware, 'run', [$this->request]);
        }
    }

    private function corsPolicy()
    {
        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Headers: content-type');
        header('Access-Control-Allow-Methods: POST, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');
    }
}
