<?php

class PagesController
{
    public function index()
    {
        require_once(VIEW.'/pages/index.html');
    }

    public function contact()
    {
        require_once(VIEW.'/pages/contact.html');
    }

    public function error()
    {
        require_once(VIEW.'/pages/error.html');
    }
}
