<?php

namespace Kriss\Nacos\Model;

class BaseModel
{
    public function load($attributes = []): self
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        return $this;
    }
}