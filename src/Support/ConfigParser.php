<?php

namespace Kriss\Nacos\Support;

use Kriss\Nacos\Enums\ConfigType;

class ConfigParser
{
    /**
     * @param string $str
     * @param string $type
     * @return array|false|mixed|object|string|null
     */
    public static function parse(string $str, string $type)
    {
        switch ($type) {
            case ConfigType::JSON:
                return Json::decode($str);
            case ConfigType::YML:
            case ConfigType::YAML:
                return yaml_parse($str);
            case ConfigType::INI:
                return parse_ini_string($str);
            case ConfigType::XML:
                simplexml_load_string($str, "SimpleXMLElement", LIBXML_NOCDATA);
                return Json::encode(Json::decode($str));
            default:
                return $str;
        }
    }
}