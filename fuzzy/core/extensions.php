<?php

/**
 * Load extensions which are in `extensions/` folder
 */
function load_extensions () {
    $extensions = glob(basepath('extensions/*.php'), GLOB_NOSORT);
    
    foreach ($extensions as $extension) {
        require $extension;
    }
}