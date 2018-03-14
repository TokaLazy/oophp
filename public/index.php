<?php

function __autoload($class) {

    $parts = preg_replace('(\\\)', '/', $class);

    require_once $parts . '.php';
}

require_once './config/config.php';
require_once './config/messages/wording.php';

use Vendor\Session;
use Vendor\Router;

Session::start();
Session::automaticConnection();

Router::request();

Session::unset('banner');
