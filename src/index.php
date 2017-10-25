<?php

session_start();

require_once('./config/config.php');
require_once(INC.'Router.php');
require_once(INC.'wording.php');

$router = new Router();
$router->request();

unset($_SESSION['flash']);
