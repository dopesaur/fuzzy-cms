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

/**
 * Lazy-load storage
 * 
 * @param string $path
 * @param array $array
 * @return \Closure
 */
function lazy_storage ($path, array $array = array()) {
    /**
     * Closure
     * 
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    return function ($key, $default = false) use (&$array, $path) {
        $dot = strpos($key, '.');
        
        $first = $dot !== false ? substr($key, 0, $dot) : $key;
        
        if (!isset($array[$first])) {
            $config = "$path/$first.php";
        
            if (file_exists($config)) {
                $array[$first] = require $config;
            }
        }
    
        return array_get($array, $key, $default);
    };
}