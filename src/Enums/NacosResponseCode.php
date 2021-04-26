<?php

namespace Kriss\Nacos\Enums;

class NacosResponseCode
{
    const OK = 200;
    const BAD_REQUEST = 400;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const SERVER_ERROR = 500;

    public static function getViewItems()
    {
        return [
            self::OK => 'OK',
            self::BAD_REQUEST => 'Bad Request',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Not Found',
            self::SERVER_ERROR => 'Internal Server Error',
        ];
    }

    public static function getDescription($code, $default = 'Unknown')
    {
        $map = static::getViewItems();
        return $map[$code] ?? $default;
    }
}
