<?php

/**
 * Set or get theme
 * 
 * @param string $new_template
 * @return string
 */
function theme ($new_template = '') {
    static $template = 'default';
    
    if ($new_template) {
        $template = $new_template;
        
        require_once BASEPATH . '/themes/' . $template . '.php';
    }
    
    return $template;
}

/**
 * View the view
 * 
 * @param string $view
 */
function view ($view, array $data = array()) {
    $view = str_replace('/', '_', $view);
    $view = preg_replace('/[^\w\d_]/', '', $view);
    
    $function = "theme_{$view}";
    $function($data);
}
