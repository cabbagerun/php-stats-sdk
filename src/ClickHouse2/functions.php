<?php

// @codeCoverageIgnoreStart
if (!function_exists('tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param mixed    $value
     * @param callable $callback
     *
     * @return mixed
     */
    function tap($value, $callback)
    {
        $callback($value);

        return $value;
    }
}
// @codeCoverageIgnoreEnd

// @codeCoverageIgnoreStart
if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @param int   $depth
     *
     * @return array
     */
    function array_flatten($array, $depth = INF): array
    {
        return array_reduce($array, function ($result, $item) use ($depth) {
            if (!is_array($item)) {
                return array_merge($result, [$item]);
            } elseif ($depth === 1) {
                return array_merge($result, array_values($item));
            } else {
                return array_merge($result, array_flatten($item, $depth - 1));
            }
        }, []);
    }
}
// @codeCoverageIgnoreEnd

if (!function_exists('raw')) {
    /**
     * Wrap string into Expression object for inserting in sql query as is.
     *
     * @param string $expr
     *
     * @return \Jianzhi\Stats\ClickHouse2\Query\Expression
     */
    function raw(string $expr): \Jianzhi\Stats\ClickHouse2\Query\Expression
    {
        return new \Jianzhi\Stats\ClickHouse2\Query\Expression($expr);
    }
}

if (!function_exists('file_from')) {
    function file_from($file): \Tinderbox\Clickhouse\Interfaces\FileInterface
    {
        if (is_string($file) && is_file($file)) {
            $file = new \Tinderbox\Clickhouse\Common\File($file);
        } elseif (is_scalar($file)) {
            $file = new \Tinderbox\Clickhouse\Common\FileFromString($file);
        }

        return $file;
    }
}
