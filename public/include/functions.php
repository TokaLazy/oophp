<?php

function logg($v) {

    if (PROD) {

        return null;

    }

    echo '<pre>';
    echo var_dump($v);
    echo '</pre>';

}

function redirect($path = '/') {

    exit(header("location:$path"));

}
