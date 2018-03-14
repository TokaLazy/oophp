<?php

namespace Vendor;

class Request {

    public static $params;

    public static function init(array $params) {

        self::$params = $params;

    }

    public static function existsParam(string $key) : bool {

        return (
            isset(self::$params[$key])
            && !empty(self::$params[$key])
        );

    }

    public static function getParam(string $key) {

        return self::existsParam($key) ? self::$params[$key] : null;

    }

}
