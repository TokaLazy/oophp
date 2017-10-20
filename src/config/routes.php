<?php

function call($controller, $model) {
    require_once(CONTROLLER."/$controller.php");

    switch($controller) {
        case 'posts':
            $controller = new PostsController();
        break;
        default:
            $controller = new PagesController();
        break;

    }

    $controller->{$model}();
}

if (file_exists(CONTROLLER."/$controller.php") && file_exists(VIEW."/$controller/$model.html")) {
    call($controller, $model);
} else {
    call('pages', 'error');
}
