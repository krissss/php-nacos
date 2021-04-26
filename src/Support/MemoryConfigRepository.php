<?php

namespace Kriss\Nacos\Support;

use Kriss\Nacos\Contract\ConfigRepositoryInterface;

class MemoryConfigRepository implements ConfigRepositoryInterface
{
    protected $items = [];

    public function __construct(array $nacosConfig, array $extraConfig = [])
    {
        $this->items['nacos'] = $nacosConfig;
        if ($extraConfig) {
            $this->items = array_merge_recursive($this->items, $extraConfig);
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->items)) {
            return $this->items[$key];
        }

        if (strpos($key, '.') === false) {
            return $this->items[$key] ?? $default;
        }

        $array = $this->items;
        foreach (explode('.', $key) as $segment) {
            if (array_key_exists($segment, $array)) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }

        return $array;
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, array $values)
    {
        $array = &$this->items;
        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $values;
    }
}