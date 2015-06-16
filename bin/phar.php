<?php

/**
 * Script which builts builders into one phar file
 * and 
 * 
 * @package Fuzzy
 */

/**
 * Get iterator for all fuzzy 
 * 
 * @param string $path
 * @return \Iterator
 */
function get_core_iterator ($path) {
    $directory = new RecursiveDirectoryIterator($path);
    $iterator = new RecursiveIteratorIterator($directory);
    
    $regex = "#^$path/(assets|content|extensions|fuzzy|themes)/[^\.]+\.[\w\d]+$#i";
    $flag  = RecursiveRegexIterator::GET_MATCH;
    
    return new RegexIterator($iterator, $regex, $flag);
}

/**
 * Create Phar arhive
 * 
 * @param string $phar
 * @param string $basepath
 * @param string $path
 */
function create_phar ($phar, $basepath, $path) {
    if (file_exists($phar)) {
        unlink($phar);
    }
    
    $phar = new Phar($phar, 0, '');
    /* $phar = $phar->convertToExecutable(Phar::TAR, Phar::GZ); */
    
    $phar->startBuffering();
    $phar->buildFromIterator(new DirectoryIterator($path), $path);
    
    foreach (get_core_iterator($basepath) as $dir) {
        $file = current($dir);
        
        $name = substr($file, strlen($basepath));
        $name = ltrim($name, '/');
        
        $phar->addFile($file, "fuzzy/$name");
    }
    
    $phar->addFile("$basepath/index.php", "fuzzy/index.php");
    $phar->stopBuffering();
}

/**
 * Entry point
 */
function main () {
    if (!Phar::canWrite()) {
        die(
            "Sorry, but I can't create Phar archives!\n" . 
            "Set phar.readonly to Off in your php.ini.\n"
        );
    }
    
    $basepath = dirname(__DIR__);
    
    $phar = __DIR__ . '/fuzzy.phar';
    $path = "$basepath/bin/php/";
    
    create_phar($phar, $basepath, $path);
}

main();