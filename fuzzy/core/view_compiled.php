<?php

/**
 * Get view function name, named `view_path` as a fallback for 
 * `view_path` function in non compiled version
 * 
 * @param string $theme
 * @param string $view
 * @return string
 */
function view_path ($theme, $view) {
    if (
        function_exists($function = "theme_{$theme}_{$view}") ||
        function_exists($function = "theme_{$view}")
    ) {
        return $function;
    }
    
    return '';
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
        
        $path = basepath("themes/$theme.php");
        
        if (file_exists($path)) {
            require_once $path;
        }
    }
    
    return $theme;
}

/**
 * View the view
 * 
 * @throws \Exception
 * @param string $__view
 * @param array $__data
 */
function view ($__view, array $__data = array()) {
    if (strpos($__view, '/') === 0) {
        return render($__view, $__data);
    }
    
    $theme = theme();
    
    $view = str_replace('/', '_', $__view);
    $view = preg_replace('/[^\w\d_]/', '', $view);
    
    $function = view_path($theme, $view);
    
    if ($function) {
        return $function($__data);
    }
    
    throw new Exception("View/layout '$view' in theme '$theme' doesn't exists!");
}
