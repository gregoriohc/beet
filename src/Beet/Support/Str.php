<?php

namespace Gregoriohc\Beet\Support;

class Str
{
    /**
     * Convert a string to snake case.
     *
     * @param  string $value
     * @param int $count
     * @param  string $delimiter
     * @return string
     */
    public static function snakePlural($value, $count = 2, $delimiter = '_')
    {
        $value = snake_case($value, $delimiter);
        $parts = explode($delimiter, $value);
        array_walk($parts, function(&$part) use ($count) {
            $part = str_plural($part, $count);
        });

        return implode($delimiter, $parts);
    }
}