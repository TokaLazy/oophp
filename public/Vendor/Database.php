<?php

namespace Vendor;

use PDO;

abstract class Database {

    private static $instance = null;

    protected static function getInstance() {

        if (!isset(self::$instance)) {

            self::$instance = new PDO('mysql:host='.SQL_HOST.';dbname='.SQL_NAME.';charset=utf8', SQL_USER, SQL_PWD);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

        }

        return self::$instance;

    }

    public function exists(string $cell, string $value) {

        $table = $this->table;

        $req = self::getInstance()
            ->prepare("SELECT *
            FROM $table
            WHERE $cell = :$cell
        ");

        $req->bindValue(":$cell", $value);
        $req->execute();

        $data = $req->fetchColumn();

        $req->closeCursor();

        return $data;

    }

}
