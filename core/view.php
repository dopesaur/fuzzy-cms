<?php

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
