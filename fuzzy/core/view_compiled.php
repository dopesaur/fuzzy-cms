<?php

/**
 * Set or get theme
 * 
 * @param string $new_theme
 * @return string
 */
function theme ($new_theme = '') {
    static $theme = 'default';
    
    if ($new_theme) {
        $theme = $new_theme;
        
        require_once sprintf('%s/themes/%s.php', BASEPATH, $theme);
    }
    
    return $theme;
}

/**
 * View the view
 * 
 * @todo decompose
 * @param string $__view
 * @param array $__data
 */
function view ($__view, array $__data = array()) {
    if (strpos($__view, '/') === 0) {
        extract($__data);
        
        require $__view;
        
        return;
    }
    
    $view = str_replace('/', '_', $__view);
    $view = preg_replace('/[^\w\d_]/', '', $view);
    
    $theme = theme();
    
    if (function_exists($function = "theme_{$theme}_{$view}")) {
        $function($__data);
    }
    else if (function_exists($function = "theme_{$view}")) {
        $function($__data);
    }
}
