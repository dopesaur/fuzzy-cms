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
    
    $function = route_exists($prefix, $suffix);
    
    if (!$function) {
        if (!route_content($route)) {
            not_found();
        }
        
        return;
    }
    
    call_user_func_array($function, $segments);
}

/**
 * Check if route exists
 * 
 * @param string $prefix
 * @param string $suffix
 * @return bool|string
 */
function route_exists ($prefix, $suffix) {
    $route = "route_{$prefix}_{$suffix}";
    $route = str_replace('-', '_', $route);
    $route = preg_replace('/[^\w\d_]/', '', $route);
    $route = trim($route, '_');
    
    return function_exists($route) ? $route : false;
}

/**
 * Route the path to content
 * 
 * @param string $path
 * @return bool
 */
function route_content ($path) {
    $path = clean_url($path);
    $path = basepath("content/$path");
    
    if (
        !file_exists("$path.md") && 
        !file_exists("$path/index.md")
    ) {
        return false;
    }
    
    $file  = file_exists("$path.md") ? "$path.md" : "$path/index.md";
    $input = process_file($file);
    
    layout(
        array_get($input, 'template', 'page'), 
        $input,
        array_get($input, 'layout', 'layout')
    );
    
    return true;
}

/**
 * Get base url (for websites in subdirectories in root)
 * 
 * @param string $root
 * @param string $base
 * @return string
 */
function base_url ($root = null, $base = null) {
    $root = trim($root ? $root : $_SERVER['DOCUMENT_ROOT'], '/');
    $base = trim($base ? $base : BASEPATH, '/');
    
    if ($root === $base) {
        return '';
    }
    
    $base_url = substr($base, strlen($root));
    
    return trim($base_url, '/');
}

/**
 * Get URL to a route
 * 
 * @param mixed ...$segments
 * @return string
 */
function url () {
    $base = base_url();
    
    $url = implode('/', func_get_args());
    $url = "$base/$url/";
    $url = trim($url, '/');
    
    return "/$url";
}

/**
 * Cleanup url
 * 
 * @param string $url
 * @return string
 */
function clean_url ($url) {
    $url = preg_replace('/\/+/', '/', $url);
    
    return str_replace('..', '', $url);
}