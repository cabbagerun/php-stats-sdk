<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Components;

use Jianzhi\Stats\ClickHouse\ComponentInterface;

/**
 * Class Select
 * @package Jianzhi\Stats\ClickHouse\Components
 */
class Select implements ComponentInterface
{
    /**
     * @var Fields
     */
    private $fields;

    /**
     * Select constructor.
     * @param array $fields
     * @throws \Exception
     */
    public function __construct(array $fields)
    {
        $this->fields = new Fields;
        $this->convertRawData($fields);
    }

    /**
     * @param array $fields
     * @throws \Exception
     */
    public function addSelect(array $fields)
    {
        $this->convertRawData($fields);
    }

    /**
     * @param array $fields
     * @throws \Exception
     */
    public function convertRawData(array $fields): void
    {
        if (!$fields) {
            $this->fields->addField(
                new Field('*')
            );
        }

        foreach ($fields as $field) {
            if (is_array($field)) {
                $this->convertRawData($field);
                continue;
            }


            if ($field instanceof Field) {
                $this->fields->addField($field);
                continue;
            }

            $this->fields->addField(
                new Field($field)
            );
        }
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "SELECT {$this->fields}";
    }
}
