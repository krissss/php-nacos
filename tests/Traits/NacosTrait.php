<?php

namespace Kriss\Nacos\Tests\Traits;

use Kriss\Nacos\Nacos;

trait NacosTrait
{
    private $nacos;

    protected function getNacos()
    {
        if (!$this->nacos) {
            $this->nacos = new Nacos(require __DIR__ . '/config.php');
        }

        return $this->nacos;
    }
}