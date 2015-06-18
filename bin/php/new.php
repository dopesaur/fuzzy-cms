<?php

/**
 * Creates new Fuzzy website
 * 
 * @arg string $directory
 */

require 'shared.php';

if (!defined('BASEPATH')) {
    define('BASEPATH', dirname(dirname(__DIR__)));
}

/**
 * Entry point
 * 
 * @param string $directory
 */
function main ($directory = '') {
    $path = BASEPATH . "/$directory/";
    $path = chop($path, '/');
    
    $fuzzy = __DIR__ . '/fuzzy';
    
    if (!file_exists($path)) {
        mkdir($path);
    }
    
    if (count(array_diff(scandir($path), array('..', '.', 'fuzzy.phar'))) > 0) {
        die(
            "The directory isn't empty!\n" .
            "Please cleaup the directory before creating new Fuzzy.\n"
        );
    }
    
    $iterator = new RecursiveDirectoryIterator($fuzzy);
    
    foreach (new RecursiveIteratorIterator($iterator) as $file) {
        $name = substr($file, strlen($fuzzy));
        
        expand_path(dirname($name), $path);
        
        copy($file, "$path/$name");
    }
    
    copy("$fuzzy/index.php", "$path/index.php");
    copy("$fuzzy/.htaccess", "$path/.htaccess");
}

call_user_func_array(
    'main', 
    array_slice($_SERVER['argv'], 1)
);