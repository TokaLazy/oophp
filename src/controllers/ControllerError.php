<?php

require_once('Controller.php');

class ErrorController extends Controller
{
    protected $folder = 'error';
    protected $title = 'ERRROR';

    public function index()
    {
        $folder = $this->folder;
        $page = __FUNCTION__;
        $title = $this->title;

        require_once(VIEW.'layout.html');
    }
}
