<?php

namespace controllers;

use Vendor\Request;

abstract class Controller {

    public function action($action) {

        if (method_exists($this, $action)) {

            $id = Request::getParam('id') ?? Request::getParam('action');

            if (!!$id) {

                $this->{$action}($id);

            } else {

                $this->{$action}();

            }

        } else {

            throw new Exception('Aucune méthode du nom de "'.$action.'" dans la class...');

        }

    }

}
