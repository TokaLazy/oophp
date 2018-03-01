<?php

class Db
{
    private static $instance = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new PDO('mysql:host='.SQL_HOST.';dbname='.SQL_NAME.';charset=utf8', SQL_USER, SQL_PWD);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        }

        return self::$instance;
    }
}
