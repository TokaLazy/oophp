<?php

require_once('./config/config.php');
require_once(INC.'Router.php');

$router = new Router();
$router->request();
