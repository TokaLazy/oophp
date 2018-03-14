<?php

namespace Vendor;

use Vendor\Request;
use Vendor\Session;
use Vendor\Form;

class Router {

    static public $request;

    public static function request() {

        Request::init($_GET);

        $controller = self::setController();
        $action = self::setAction();

        Session::setPage($action);

        $controller->action($action);

        foreach (Form::$data as $key => $value) {

            $$key = $value;

        }

        require_once './layout.php';

    }

    private function checkController($controller) {

        $controller = 'Controller'.ucfirst(strtolower($controller));
        $controllerFile = CONTROLLER."/$controller.php";

        if (!file_exists($controllerFile)) {

            return self::checkController('error');

        }

        require_once $controllerFile;

        $controller = new $controller();

        return $controller;
    }

    private function setController() {

        $controller = 'accueil';

        if (Request::existsParam('controller')) {

            $controller = Request::getParam('controller');

        }

        return self::checkController($controller);
    }

    private function setAction() {

        $action = 'index';

        if (Request::existsParam('action')) {

            if (!intval(Request::getParam('action'))) {

                $action = Request::getParam('action');

            } else {

                $action = 'show';

            }

        }

        return $action;
    }

}
