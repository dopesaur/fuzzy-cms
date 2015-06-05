<?php

/**
 * Get config value by key
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function config ($key, $default = false) {
    static $storage = null;
    
    $storage or $storage = lazy_storage(basepath('content/_config'));
    
    return $storage($key, $default);
}

/**
 * Get data value by key
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function data ($key, $default = false) {
    static $storage = null;
    
    $storage or $storage = lazy_storage(basepath('content/_data'));
    
    return $storage($key, $default);
}