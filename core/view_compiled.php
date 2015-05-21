<?php

/**
 * View the view
 * 
 * @param string $view
 */
function view ($view, array $data = array()) {
    $view = preg_replace('/[^\w\d_]/', '', $view);
    $view = str_replace('/', '_', $view);
    
    $function = "theme_{$view}";
    
    if (function_exists($function)) {
        $function($data);
    }
}
