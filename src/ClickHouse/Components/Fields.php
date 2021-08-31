<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;

use Jianzhi\Stats\ClickHouse\ComponentInterface;

class Fields implements \Iterator, ComponentInterface
{
    /**
     * @var Field[]
     */
    private $fields;

    /**
     * @return Field
     */
    public function current(): Field
    {
        return current($this->fields);
    }

    /**
     * @return int|mixed|null|string
     */
    public function key()
    {
        return key($this->fields);
    }

    /**
     * @return Field
     */
    public function next(): Field
    {
        return next($this->fields);
    }

    public function rewind(): Field
    {
        return reset($this->fields);
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->fields[$this->key()]);
    }

    /**
     * @return Field[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param Field $field
     * @return Fields
     */
    public function addField(Field $field): self
    {
        $this->fields[] = $field;
        return $this;
    }

    public function __toString(): string
    {
        $sql = [];

        foreach ($this->fields as $field) {
            $sql[] = (string) $field;
        }

        return implode(',', $sql);
    }
}