<?php

require 'shared.php';

error_reporting(0);

/**
 * Another build file, 
 * renders whole structure into static website
 * 
 * @package Fuzzy
 */

define('BASEPATH', dirname(__DIR__));

/**
 * Get all content files
 * 
 * @return array
 */
function content_files () {
    return glob_recursive(BASEPATH . '/content/*.md');
}

/**
 * Expand the path
 * 
 * @param string $path
 */
function expand_path ($path, $destination) {
    $path  = trim($path, '/');
    $frags = explode('/', $path);
    $temp  = '';
    
    while (!empty($frags)) {
        $frag = array_shift($frags);
        
        $temp .= $frag . '/';
        
        if (!file_exists("$destination/$temp")) {
            mkdir("$destination/$temp");
        }
    }
}

/**
 * Entry point
 * 
 * @param string $destination
 * @param string $basepath
 */
function main ($destination, $basepath = '') {
    require BASEPATH . '/fuzzy/core/index.php';
    
    load_core();
    load_extensions();
    
    $_SERVER['DOCUMENT_ROOT'] = BASEPATH;
    
    date_default_timezone_set(config('general.timezone', 'Europe/London'));
        
    foreach (content_files() as $file) {
        $path = substr($file, strlen(BASEPATH) + strlen('/content/'));
        $path = substr($path, 0, strpos($path, '.'));
        
        $index = strlen($path) - strlen('index');
        
        if (strpos($path, 'index') === $index) {
            $path = strpos($path, 0, $index);
        }
        
        $content = capture(function () use ($path) {
            return route_content($path);
        });
        
        expand_path($path, $destination);
        
        file_put_contents("$destination{$path}/index.html", $content);
    }
}

$args = count($_SERVER['argv']);

if ($args <= 1) {
    main(BASEPATH . '/static/');
}
else if ($args > 2) {
    list($file, $destination, $basepath) = $_SERVER['argv'];
    
    main($destination, $basepath);
}