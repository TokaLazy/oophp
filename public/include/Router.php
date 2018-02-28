<?php

require_once('Request.php');

class Router
{
    public function request()
    {
        $request = new Request($_GET);

        $controller = $this->setController($request);
        $action = $this->setAction($request);

        $controller->action($action);
    }

    public function checkController($controller, $request)
    {
        $controller = ucfirst(strtolower($controller));
        $controllerFile = CONTROLLER."/Controller$controller.php";
        $controllerClass = $controller.'Controller';

        if (!file_exists($controllerFile)) {
            return $this->checkController('error', $request);
        }

        require_once($controllerFile);

        $controller = new $controllerClass();
        $controller->setRequest($request);

        return $controller;
    }

    public function setController($request)
    {
        $controller = 'accueil';

        if ($request->existParam('controller')) {
            $controller = $request->getParam('controller');
        }

        return $this->checkController($controller, $request);
    }

    public function setAction($request)
    {
        $action = 'index';

        if ($request->existParam('action')) {
            if (!intval($request->getParam('action'))) {
                $action = $request->getParam('action');
            } else {
                $action = 'show';
            }
        }

        return $action;
    }
}
