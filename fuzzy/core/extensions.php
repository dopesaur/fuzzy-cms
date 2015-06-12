<?php

/**
 * Add or get all routes extension
 * 
 * @param string $route
 * @return void|array
 */
function extension_routes (Closure $route = null) {
    static $routes = array();
    
    if ($route) {
        $routes[] = $route;
        
        return;
    }
    
    $temp = array();
    
    foreach ($routes as $route) {
        $temp = array_merge($temp, $route());
    }
    
    return $temp;
}

/**
 * Load extensions which are in `extensions/` folder
 */
function load_extensions () {
    $path = basepath('extensions/*.php');
    
    foreach (glob($path, GLOB_NOSORT) as $extension) {
        require $extension;
    }
}