<?php

/**
 * Phar dispatcher.
 * 
 * Each file in following directory is a command (like a pattern).
 * 
 * @package Fuzzy
 */

$args = array_slice($_SERVER['argv'], 1);

$file = current($args);
$path = __DIR__ . "/$file.php";

define('BASEPATH', getcwd());

if (file_exists($path) && $file !== 'shared') {
    $_SERVER['argv'] = $args;
    
    require $path;
}