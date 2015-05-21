<?php

/**
 * Dispatch a route
 * 
 * {$prefix}_{$suffix} (...$parameters)
 * 
 * @param string $route
 */
function dispatch ($route) {
    $route = trim($route, '/');
    
    $segments = explode('/', $route);
    
    $prefix = array_shift($segments);
    $prefix = $prefix ? $prefix : 'index';
    
    $suffix = array_shift($segments);
    $suffix = $suffix ? $suffix : 'index';
    
    $function = "route_{$prefix}_{$suffix}";
    $function = str_replace('-', '_', $function);
    $function = preg_replace('/[^\w\d_]/', '', $function);
    $function = trim($function, '_');
    
    if (!function_exists($function)) {
        not_found();
    }
    
    call_user_func_array($function, $segments);
}