<?php

namespace Kriss\Nacos\DTO\Request;

class SwitchParams
{
    /**
     * @var string
     */
    private $entry;
    /**
     * @var string
     */
    private $value;
    /**
     * @var boolean
     */
    private $debug;

    public function __construct(string $entry, string $value)
    {
        $this->entry = $entry;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getEntry(): string
    {
        return $this->entry;
    }

    /**
     * @param string $entry
     * @return SwitchParams
     */
    public function setEntry(string $entry): SwitchParams
    {
        $this->entry = $entry;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return SwitchParams
     */
    public function setValue(string $value): SwitchParams
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     * @return SwitchParams
     */
    public function setDebug(bool $debug): SwitchParams
    {
        $this->debug = $debug;
        return $this;
    }
}