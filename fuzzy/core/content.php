<?php

/**
 * Browse content API
 * 
 * Functions for managing content files 
 */

/**
 * Browse content
 * 
 * @param string $path
 * @return array
 */
function browse_content ($path = '') {
    $path = rtrim(basepath("content/$path"), '/');
    
    $files = glob("$path/*");
    
    return array_filter($files, function ($file) {
        return strpos($file, '.') !== 0 || !is_dir($file);
    });
}

/**
 * Check whether the input filepath is a content file
 * 
 * @param string $file
 * @return bool
 */
function is_content_file ($file) {
    $path = basepath('content');
    
    return strpos($file, $path) === 0;
}

/**
 * Check wheter content file exists.
 * 
 * **Don't forget to provide the extension** with filepath
 * 
 * @param string $file
 * @return bool
 */
function content_file_exists ($file) {
    $path = basepath("content/$file");
    
    return file_exists($path);
}

/**
 * Get path to content file
 * 
 * @param string $path
 * @return string
 */
function content_path ($path) {
    $path = trim($path, '/');
    $path = clean_url($path);
    $path = basepath("content/$path");
    
    $directory = file_exists("$path/index.md");
    $file      = file_exists("$path.md");
    
    if (!$file && !$directory) {
        return false;
    }
    
    return $file ? "$path.md" : "$path/index.md";
}