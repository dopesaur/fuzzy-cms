<?php

/**
 * Recursive glob
 * 
 * @link http://in.php.net/manual/en/function.glob.php#106595
 * @param string $pattern
 * @param int $flags
 */
function glob_recursive ($pattern, $flags = 0) {
    $files = glob($pattern, $flags);
    $dirname = dirname($pattern);
    
    foreach (glob("$dirname/*", GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
        $basename = basename($pattern);
        
        $files = array_merge($files, glob_recursive("$dir/$basename", $flags));
    }
    
    return $files;
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
