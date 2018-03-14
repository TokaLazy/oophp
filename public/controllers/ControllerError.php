<?php

use Vendor\Session;
use controllers\Controller;

class ControllerError extends Controller {

    public function index() {

        Session::setFolder('error');
        Session::setTitle('ERROR');

        Session::setBreadcrumb(['Accueil' => '/']);

    }

}
