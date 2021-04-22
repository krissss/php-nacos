<?php

namespace Kriss\Nacos\Support;

class Json
{
    /**
     * @param array|string $data
     * @return string|null
     */
    public static function encode($data): ?string
    {
        $result = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
        if (!$result) {
            return null;
        }
        return $result;
    }

    /**
     * @param $json
     * @param bool $toArray
     * @return array|object|null
     */
    public static function decode($json, $toArray = true)
    {
        $result = json_decode($json, $toArray);
        if (!$result) {
            return null;
        }
        return $result;
    }
}