<?php

/**
 * Get path to theme view
 * 
 * @param string $theme
 * @param string $file
 * @return string
 */
function view_path ($theme, $file) {
    return sprintf('%s/themes/%s/%s.php', BASEPATH, $theme, $file);
}

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
    }
    
    if (file_exists($functions = view_path($theme, 'functions'))) {
        require_once $functions;
    }
    
    return $theme;
}

/**
 * View the view
 * 
 * @param string $__view
 * @param array $__data
 */
function view ($__view, array $__data = array()) {
    extract($__data);
    
    require view_path(theme(), $__view);
}