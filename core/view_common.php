<?php

/**
 * Set/get theme
 * 
 * @param string $new_template
 * @return string
 */
function theme ($new_template = '') {
    static $template = 'default';
    
    if ($new_template) {
        $template = $new_template;
    }
    
    return $template;
}

/**
 * View the layout
 * 
 * @param string $view
 * @param array $data
 */
function layout ($view, array $data = array()) {
    $data['view'] = $view;
    
    view('layout', $data);
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
    $path = trim($path, '/');
    
    header("Location: /$path") and exit;
}
