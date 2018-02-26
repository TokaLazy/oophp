<?php

require_once('Controller.php');
require_once(INC.'Session.php');

class ErrorController extends Controller
{
    protected $folder = 'error';
    protected $title = 'ERRROR';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        Session::setAriane(['Accueil' => '/']);

        require_once('./layout.php');
    }
}
