<?php

/**
 * Load extensions which are in `extensions/` folder
 */
function load_extensions () {
    $path = basepath('extensions/*.php');
    
    foreach (glob($path, GLOB_NOSORT) as $extension) {
        require $extension;
    }
}