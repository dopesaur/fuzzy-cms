<?php

require 'shared.php';

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
    $path  = '';
    
    while (!empty($frags)) {
        $frag  = array_shift($frags);
        $path .= "$frag/";
        
        $fullpath = "$destination/$path";
        
        if (!file_exists($fullpath)) {
            mkdir($fullpath);
        }
    }
}

/**
 * Construct URL path to content files
 * 
 * @param string $file
 * @return string
 */
function construct_path ($file) {
    $path = substr($file, strlen(BASEPATH) + strlen('/content/'));
    $path = substr($path, 0, strpos($path, '.'));
    
    $index = strlen($path) - strlen('index');
    
    if (strpos($path, 'index') === $index) {
        $path = strpos($path, 0, $index);
    }
    
    return $path;
}

/**
 * Capture content for file
 * 
 * @param string $path
 * @return string
 */
function capture_content ($path) {
    $content = capture(function () use ($path) {
        return route_content($path);
    });
    
    return str_replace(
        array('href="/', 'src="/'),
        array('href="', 'src="'),
        $content
    );
}

/**
 * Process the content (add <base>)
 * 
 * @param string $basepath
 * @param string $content
 * @return 
 */
function process_content ($basepath, $content) {
    $document = new DOMDocument;
    
    libxml_use_internal_errors(true);
    $document->loadHTML($content);
    
    $document->preserveWhiteSpace = false;
    $document->formatOutput = true;
    
    $base = $document->createElement('base');
    $base->setAttribute('href', "$basepath/");
    
    $document->getElementsByTagName('head')
             ->item(0)
             ->appendChild($base);
    $document->normalizeDocument();
    
    return $document->saveHTML();
}

/**
 * Entry point
 * 
 * @param string $destination
 * @param string $basepath
 */
function main ($destination, $basepath = '') {
    load_core();
    load_extensions();
    
    array_set($_SERVER, 'DOCUMENT_ROOT', BASEPATH);
    date_default_timezone_set(config('general.timezone', 'Europe/London'));
    
    $destination = trim($destination, '/') . '/';
    $basepath = chop("/$basepath/", '/');
        
    foreach (content_files() as $file) {
        $path = construct_path($file);
        
        $content  = capture_content($path);
        $filepath = $destination . $path . '/index.html';
        
        expand_path($path, $destination);
        
        file_put_contents($filepath, process_content($basepath, $content));
    }
}

/** Run, baby */
require BASEPATH . '/fuzzy/core/index.php';

call_user_func_array(
    'main', 
    array_slice($_SERVER['argv'], 1)
);