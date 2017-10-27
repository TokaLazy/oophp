<?php

require_once('./config/config.php');
require_once(INC.'Session.php');
require_once(INC.'Router.php');
require_once(INC.'wording.php');

$session = new Session();
$session->autoConnect();

$router = new Router();
$router->request();

$session->unset('flash');
