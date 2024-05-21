<?php

namespace plugse\server\core\infra\http;

use plugse\server\core\helpers\File;
use plugse\server\core\infra\http\Request;
use plugse\server\core\infra\http\routes\Router;
use plugse\server\core\errors\ActionNotFoundError;

class Bootstrap
{
    private Router $router;

    public function __construct() {
        $this->router = new Router(File::getFileData('src/infra/http/routes/api.php'));
    }

    public function run()
    {
        try {
            $request = $this->router->getRequest();
            $this->runMidlewares($request);
            $response = $this->runAction($request);
            
            echo json_encode($response->get());
        } catch (\Throwable $th) {
            echo json_encode(['error'=>$th->getMessage()], JSON_PRETTY_PRINT);
        }
        
    }

    private function runAction(Request $request)
    {
        $controller = File::runClass($request->route->controller);
        $action = $request->route->action;
        if(!method_exists($controller, $action)){
            throw new ActionNotFoundError($action, $request->route->controller);
        }
        
        return $controller->$action($request);
    }

    private function runMidlewares(Request $request)
    {
        foreach($request->route->midwares as $midleware){
            File::runClass($midleware);
        }
    }
}
