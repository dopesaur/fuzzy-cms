<?php

/**
 * Render a (php) file view
 * 
 * @param string $__view
 * @param array $__data
 */
function render ($__view, array $__data = array()) {
    extract($__data);
    
    require $__view;
}

/**
 * View the layout
 * 
 * @param string $view
 * @param array $data
 * @param string $layout
 */
function layout ($view, array $data = array(), $layout = 'layout') {
    $data['view'] = $view;
    
    view($layout, $data);
}

/**
 * Display 404 page
 */
function not_found () {
    header('HTTP/1.1 404 Not Found');
    
    die('404 - Not Found');
}

/**
 * Redirect to URL
 * 
 * @param string $path
 */
function redirect ($path) {
    $path = trim(url($path), '/');
    
    header("Location: /$path") and exit;
}

/**
 * Capture output
 * 
 * @param callable $callback
 * @return string
 */
function capture ($callback) {
    ob_start();
    
    $callback();
    
    return ob_get_clean();
}

/**
 * Convert URL to a name
 * 
 * @param string $url
 * @return string
 */
function url_to_name ($url) {
    $url = str_replace('-', '_', $url);
    
    return preg_replace('/[^\w\d_]/', '', $url);
}