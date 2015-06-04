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
 * @param string $view
 */
function view ($view, array $data = array()) {
    $view = str_replace('/', '_', $view);
    $view = preg_replace('/[^\w\d_]/', '', $view);
    
    $theme = theme();
    
    if (function_exists($function = "theme_{$theme}_{$view}")) {
        $function($data);
    }
    else if (function_exists($function = "theme_{$view}")) {
        $function($data);
    }
}
