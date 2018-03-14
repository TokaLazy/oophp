<?php

use Vendor\Session;
use controllers\Controller;

class ControllerDeconnexion extends Controller {

    public function index() {

        Session::destroy();
        Session::setBanner('success', 'Vous êtes à présent déconnecté.');

        redirect();

    }

}
