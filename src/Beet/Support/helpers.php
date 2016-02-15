<?php

use Gregoriohc\Beet\Support\Str;

if (! function_exists('snake_case_plural')) {
    /**
     * Convert a string to snake case with plural words.
     *
     * @param  string  $value
     * @param  int $count
     * @param  string  $delimiter
     * @return string
     */
    function snake_case_plural($value, $count = 2, $delimiter = '_')
    {
        return Str::snakePlural($value, $count, $delimiter);
    }
}

if (! function_exists('class_ancestor')) {
    /**
     * Convert a string to snake case with plural words.
     *
     * @param  string  $class
     * @return boolean
     */
    function class_ancestor($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        $classBaseName = class_basename($class);
        $classNamespace = substr($class, 0, -(strlen($classBaseName)));

        $parts = explode('_', snake_case($classBaseName));
        if (1 == count($parts)) {
            return false;
        }

        array_pop($parts);
        $ancestorBaseName = studly_case(implode('_', $parts));

        if (class_exists($classNamespace.$ancestorBaseName)) {
            return $classNamespace.$ancestorBaseName;
        }

        return false;
    }
}
