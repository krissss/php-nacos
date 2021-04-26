<?php

namespace Kriss\Nacos\Exceptions;

use RuntimeException;
use Throwable;

class NacosException extends RuntimeException
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
