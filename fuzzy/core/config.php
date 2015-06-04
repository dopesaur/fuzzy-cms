<?php

/**
 * Get config value by key
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function config ($key, $default = false) {
    static $array = array();
    
    if (!isset($array[$key])) {
        $first = current(explode('.', $key));
        
        $config = basepath("content/_config/$first.php");
        
        if (file_exists($config)) {
            $array[$first] = require $config;
        }
    }
    
    return array_get($array, $key, $default);
}