<?php

/**
 * Storage function, an alternative to global arrays
 * 
 * @param array $default
 * @return \Closure
 */
function storage (array $default = array()) {
    /**
     * Polymorphic callback, which stores $default data
     * in the closure
     * 
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    return function ($key = null, $value = null) use (&$default) {
        if ($key !== null && $value === null) {
            return array_get($default, $key);
        }
        
        if ($key !== null && $value !== null) {
            array_set($default, $key, $value);
            
            return;
        }
        
        return $default;
    };
}