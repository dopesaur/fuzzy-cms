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
    }
    
    return $template;
}

/**
 * View the view
 * 
 * @param string $__view
 * @param array $__data
 */
function view ($__view, array $__data = array()) {
    extract($__data);
    
    require sprintf('%s/themes/%s/%s.php', BASEPATH, theme(), $__view);
}
