<?php

namespace Kriss\Nacos\Exceptions;

use Kriss\Nacos\Enums\NacosResponseCode;
use Throwable;

class ConfigNotFoundException extends NacosException
{
    public function __construct($message = "", $code = NacosResponseCode::NOT_FOUND, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}