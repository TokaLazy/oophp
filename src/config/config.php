<?php

$prod = isset($_SERVER["HTTP_X_REAL_IP"]) && $_SERVER["HTTP_X_REAL_IP"] == $_SERVER["REMOTE_ADDR"] && $_SERVER["SERVER_ADDR"] != $_SERVER["REMOTE_ADDR"];

// ENVIRONMENT
define('PROD', $prod);

define('CONF', './config/');
define('INC', './include/');

// ASSETS path
define('ASSET', PROD ? '/assets' : '/resources');

// STYLESHEETS path
define('CSS', PROD ? ASSET.'/css' : ASSET.'/scss');

// IMAGES path
define('IMG', ASSET.'/img/');

// JAVASCRIPTS path
define('JS', ASSET.'/js');

// CONTROLLER path
define('CONTROLLER', './controllers/');

// MODEL path
define('MODEL', './models/');

// VIEWS path
define('VIEW', './views/');


require_once('sql.php');
require_once(INC.'functions.php');
