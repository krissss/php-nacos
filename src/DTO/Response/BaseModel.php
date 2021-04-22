<?php

namespace Kriss\Nacos\DTO\Response;

abstract class BaseModel
{
    protected $attributes;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;

        foreach ($this->specialTypes() as $attribute => $type) {
            if (!isset($this->attributes[$attribute])) {
                continue;
            }
            $value = $this->attributes[$attribute];
            if (is_string($type) && class_exists($type) && is_a($type, self::class, true)) {
                $this->attributes[$attribute] = new $type($value);
                continue;
            }
            if (is_callable($type)) {
                $this->attributes[$attribute] = call_user_func($type, $value, $this->attributes);
            }
        }
    }

    /**
     * @return array [$attribute => BaseModel::class, $attribute => callable($value)]
     */
    protected function specialTypes(): array
    {
        return [];
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }
}
