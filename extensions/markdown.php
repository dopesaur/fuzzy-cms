<?php

require basepath('extensions/markdown/Parsedown.php');

/**
 * Process markdown via Parsedown
 * 
 * @param string $markdown
 * @return string
 */
function markdown ($markdown) {
    static $parse = null;
    
    $parse or $parse = new Parsedown;
    
    return $parse->text($markdown);
}

processors('markdown', function ($config) { 
    return markdown($config); 
});