<?php

/**
 * Browse content API
 * 
 * Functions for managing content files 
 */

/**
 * Browse content
 * 
 * @todo refactor
 * @param string $path
 * @return array
 */
function browse_content ($path = '') {
    $path = rtrim(basepath("content/$path"), '/');
    
    $lenght = strlen(basepath('content/'));
    
    $files = glob("$path/*");
    
    $files = array_map(
        function ($file) use ($lenght) {
            $file = substr($file, $lenght);
            
            return substr($file, 0, strpos($file, '.'));
        }, 
        $files
    );
    
    return array_filter($files, function ($file) {
        return strpos($file, '.') !== 0
            || is_dir($file) === false;
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