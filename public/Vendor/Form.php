<?php

namespace Vendor;

class Form {

    public static $data = [];

    public static function setData(string $key, $value) {

        self::$data[$key] = $value;

    }

    public static function getData(string $key) {

        return self::$data[$key];

    }

}
