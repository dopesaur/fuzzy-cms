<?php

require 'shared.php';

if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(dirname(__DIR__)));
}

/**
 * Entry point
 * 
 * @param string $directory
 */
function main () {
    $path  = BASEPATH;
    $fuzzy = __DIR__ . '/fuzzy';
    
    if (count(array_diff(scandir($path), array('..', '.'))) > 0) {
        die(
            "The directory isn't empty!\n" .
            "Please cleaup the directory before using installing Fuzzy.\n"
        );
    }
    
    $iterator = new RecursiveDirectoryIterator($fuzzy);
    
    foreach (new RecursiveIteratorIterator($iterator) as $file) {
        $name = substr($file, strlen($fuzzy));
        
        expand_path(dirname($name), $path);
        
        copy($file, "$path/$name");
    }
    
    copy("$fuzzy/index.php", "$path/index.php");
}

call_user_func_array(
    'main', 
    array_slice($_SERVER['argv'], 1)
);