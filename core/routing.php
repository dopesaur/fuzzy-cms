<?php

/**
 * Dispatch a route
 * 
 * {$prefix}_{$suffix} (...$parameters)
 * 
 * @param string $route
 * @param array $whitelist
 */
function dispatch ($route, array $whitelist = array()) {
    $route = trim($route, '/');
    
    $segments = explode('/', $route);
    
    $prefix = array_shift($segments);
    $prefix = $prefix ? $prefix : 'index';
    
    $suffix = array_shift($segments);
    $suffix = $suffix ? $suffix : 'index';
    
    $function = "{$prefix}_{$suffix}";
    $function = preg_replace('/[^\w\d_]/', '', $function);
    $function = trim($function, '_');
    
    if (
        !function_exists($function) ||
        (
            !empty($whitelist) &&
            !in_array($function, $whitelist)
        )
    ) {
        return false;
    }
    
    call_user_func_array($function, $segments);
}