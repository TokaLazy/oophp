<?php

require_once('Controller.php');

class AccueilController extends Controller
{
    protected $folder = 'accueil';
    protected $title = 'Page d\'accueil';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        require_once(VIEW.'layout.html');
    }
}
