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
    $suffix = array_shift($segments);
    
    if (!$function = route_exists($prefix, $suffix)) {
        not_found();
    }
    
    call_user_func_array($function, $segments);
}

/**
 * Check if route exists
 * 
 * @param string $prefix
 * @param string $suffix
 * @return string
 */
function route_exists ($prefix, $suffix) {
    $prefix = $prefix ? $prefix : 'index';
    $suffix = $suffix ? $suffix : 'index';
    
    $route = url_to_name("route_{$prefix}_{$suffix}");
    $route = trim($route, '_');
    
    return function_exists($route) ? $route : '';
}

/**
 * Route the path to content
 * 
 * @todo decompose
 * @param string $path
 * @return bool
 */
function route_content ($path) {
    if (preg_match('/_[\w\d\_\-]/', $path)) {
        not_found();
    }
    
    $path = clean_url($path);
    $path = basepath("content/$path");
    
    $file = content_path($path);
    
    if (!$file) {
        return false;
    }
    
    $input = process_file($file);
    
    $layout = array_get($input, 'layout');
    $layout = basepath("content/_layouts/$layout.php");
    
    layout(file_exists($layout) ? $layout : 'page', $input);
    
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
    $root = $root === '' ? BASEPATH : $root;
    
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