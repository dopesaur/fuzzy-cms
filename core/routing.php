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

/**
 * Get base url (for websites in subdirectories in root)
 * 
 * @param string $root
 * @param string $base
 */
function base_url ($root = null, $base = null) {
    $root = trim($root ? $root : $_SERVER['DOCUMENT_ROOT'], '/');
    $base = trim($base ? $base : BASEPATH, '/');
    
    // BASEPATH and DOCUMENT_ROOT are the same
    if ($root === $base) {
        return '';
    }
    
    $base_url = substr($base, strlen($root));
    
    return trim($base_url, '/');
}

/**
 * Get URL to a route
 * 
 * @param mixed ...$url_segments
 */
function url () {
    $url = '';
    
    if (func_num_args() !== 0) {
        $url = implode('/', func_get_args());
    }
    
    $url = base_url() . "/$url/";
    $url = trim($url, '/');
    
    return "/$url";
}