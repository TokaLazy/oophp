<?php

require_once('Controller.php');
require_once(INC.'Session.php');

class DeconnexionController extends Controller
{
    public function index()
    {
        Session::destroy();
        Session::setFlash('success', 'Vous êtes à présent déconnecté.');
        redirect();
    }
}
