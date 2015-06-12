<?php

/**
 * Another build file, 
 * renders whole structure into static website
 * 
 * @package Fuzzy
 */

define('BASEPATH', dirname(dirname(__DIR__)));

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
    
    return replace_links($content);
}

/**
 * Capture the content of route
 * 
 * @param string $path
 * @return string
 */
function capture_route ($path) {
    $content = capture(function () use ($path) {
        return dispatch($path);
    });
    
    return replace_links($content);
}

/**
 * Replace absolute attribute links to relative
 * 
 * @param string $content
 * @return string
 */
function replace_links ($content) {
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
    
    $title = $document->getElementsByTagName('title')
                      ->item(0);
    
    $document->getElementsByTagName('head')
             ->item(0)
             ->insertBefore($base, $title);
    
    return $document->saveHTML();
}

/**
 * Save content
 * 
 * @param string $content
 * @param string $path
 * @param string $destination
 * @param string $basepath
 */
function save_content ($content, $path, $destination, $basepath) {
    $filepath = $destination . $path . '/index.html';
    
    expand_path($path, $destination);
    
    file_put_contents($filepath, process_content($basepath, $content));
}

/**
 * Save route content to file
 * 
 * @param string $path
 * @param array $parameters
 */
function save_route ($path, $parameters, $destination, $basepath) {
    foreach ($parameters as $parameter) {
        if (!is_array($parameter)) {
            $parameter = array($parameter);
        }
    
        $route = path_to_route($path, $parameter);
        
        save_content(
            capture_route($route),
            $route, 
            $destination, $basepath
        );
    }
}

/**
 * Path to route
 * 
 * @param string $path
 * @return string
 */
function path_to_route ($path, $parameter) {
    $route = chop($path, '/');
    $route .= '/';
    $route .= implode('/', $parameter);
    
    return trim($route, '/');
}

/**
 * Entry point
 * 
 * @todo decompose
 * @param string $destination
 * @param string $basepath
 */
function main ($destination = 'static', $basepath = '') {
    $destination = trim($destination, '/') . '/';
    $basepath = chop("/$basepath/", '/');
    
    foreach (content_files() as $file) {
        $path = construct_path($file);
        
        save_content(capture_content($path), $path, $destination, $basepath);
    }
    
    foreach (extension_routes() as $path => $parameters) {
        save_route($path, $parameters, $destination, $basepath);
    }
}

/** Run, baby */
require 'shared.php';
require BASEPATH . '/fuzzy/core/index.php';

load_core();
load_extensions();

array_set($_SERVER, 'DOCUMENT_ROOT', BASEPATH);
date_default_timezone_set(config('general.timezone', 'Europe/London'));

db_connect(basepath('content/db.sqlite'));

call_user_func_array(
    'main', 
    array_slice($_SERVER['argv'], 1)
);