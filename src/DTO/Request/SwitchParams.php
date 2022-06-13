<?php

namespace Kriss\Nacos\DTO\Request;

class SwitchParams
{
    private string $entry;
    private string $value;
    private ?bool $debug = null;

    public function __construct(string $entry, string $value)
    {
        $this->entry = $entry;
        $this->value = $value;
    }

    public function getEntry(): string
    {
        return $this->entry;
    }

    public function setEntry(string $entry): self
    {
        $this->entry = $entry;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function getDebug(): ?bool
    {
        return $this->debug;
    }

    public function setDebug(?bool $debug): self
    {
        $this->debug = $debug;
        return $this;
    }
}