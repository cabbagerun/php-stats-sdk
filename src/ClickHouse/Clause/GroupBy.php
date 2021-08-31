<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Clause;

/**
 * Class GroupBy
 * @package Jianzhi\Stats\ClickHouse\Clause
 */
class GroupBy
{
    private $groups = [];

    /**
     * GroupBy constructor.
     * @param $params
     * @throws \Exception
     */
    public function __construct($params)
    {
        $this->parseInput($params);
    }

    /**
     * @param $params
     * @throws \Exception
     */
    private function parseInput($params): void
    {
        $params = $this->convertToArray($params);
        $params = $this->convertArrayDeclaration($params);

        foreach ($params as $value) {
            $this->setGroup($value);
        }
    }

    /**
     * @param $params
     * @return array|mixed
     * @throws \Exception
     */
    private function convertArrayDeclaration($params): array
    {
        $firstElement = reset($params);

        if (is_array($firstElement)) {
            if (count($params) > 1) {
                throw new \Exception('With massive directional format, only 1 parameter is allowed');
            }
            $params = $firstElement;
        }
        
        return $params;
    }

    /**
     * @param $params
     * @return array
     * @throws \Exception
     */
    private function convertToArray($params): array
    {
        if (!is_array($params) && is_string($params)) {
            return [$params];
        }

        if (is_array($params)) {
            return $params;
        }

        throw new \Exception('must be string or array');
    }

    private function setGroup($field)
    {
        $this->groups[] = $field;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $sql = implode(', ', $this->groups);

        return "GROUP BY {$sql}";
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function addGroup($params)
    {
        $this->parseInput($params);
    }
}
