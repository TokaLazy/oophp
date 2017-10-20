<?php

require_once('./config/config.php');
require_once(CONF.'/functions.php');
require_once(CONF.'/connection.php');

$controller = !empty($_GET['controller']) ? $_GET['controller'] : 'pages';
$model = !empty($_GET['model']) ? $_GET['model'] : 'index';

require_once(VIEW.'/layout.html');
