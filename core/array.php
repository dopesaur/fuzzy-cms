<?php

/**
 * Get a key from array if is set
 * 
 * @param array $array
 * @param string $key
 * @param mixed $default
 */
function array_get (array $array, $key, $default = false) {
    if (isset($array[$key])) {
        return $array[$key];
    }
    
    return $default;
}