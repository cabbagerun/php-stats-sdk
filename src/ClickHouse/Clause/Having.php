<?php

declare(strict_types=1);

namespace Jianzhi\Stats\ClickHouse\Clause;

/**
 * Class Limit
 * @package Jianzhi\Stats\ClickHouse\Clause
 */
class Having
{
    private $havings = [];

    /**
     * Having constructor.
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

        foreach ($params as $key => $value) {
            if (!is_numeric($key)) {
                $this->setHaving($key, $value);
                continue;
            }

            if (is_numeric($key)) {

                if (strpos($value, ',') !== false) {
                    $rows = array_map('trim', explode(',', $value));

                    foreach ($rows as $row) {
                        $this->splitBySpace($row);
                    }
                } else {
                    $this->splitBySpace($value);
                }
            }
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
            $firstElementValue = reset($firstElement);
            if (count($firstElementValue) != 2) {
                throw new \Exception('With massive directional format, only 2 parameter is allowed');
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

    private function splitBySpace(string $input)
    {
        if (strpos($input, ' ') !== false) {
            $keyVal = array_map('trim', explode(' ', $input));
            if (count($keyVal) != 3) {
                throw new \Exception('The field must be a blank direction');
            }
            $key = array_shift($keyVal);
            $this->setHaving($key, $keyVal);
        }
    }

    private function setHaving($input, array $direction)
    {
        $this->havings[] = [
            'input' => $input,
            'direction' => array_values($direction)
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $blocks = [];

        foreach ($this->havings as $having) {
            $direction = $having['direction'];
            $blocks[] = "{$having['input']} {$direction[0]} {$direction[1]}";
        }

        $sql = implode(' and ', $blocks);

        return "HAVING {$sql}";
    }

    /**
     * @param $params
     * @throws \Exception
     */
    public function addHaving($params)
    {
        $this->parseInput($params);
    }
}
