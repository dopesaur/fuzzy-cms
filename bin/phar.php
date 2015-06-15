<?php

/**
 * Script which builts builders into one phar file
 * 
 * @package Fuzzy
 */

/**
 * Entry point
 */
function main () {
    if (!Phar::canWrite()) {
        die("Sorry, but I can't write to Phar :(");
    }
    
    $basepath = dirname(__DIR__);
    
    $phar  = __DIR__ . '/fuzzy.phar';
    $path  = "$basepath/bin/php/";
    $fuzzy = "$basepath/fuzzy/";
    
    try {
        if (file_exists($phar)) {
            unlink($phar);
        }
        
        $phar = new Phar($phar, 0, '');
        
        $iterator = new AppendIterator;
        $iterator->append(new DirectoryIterator($path));
        
        $phar->startBuffering();
        $phar->buildFromIterator($iterator, $path);
        
        $phar->setMetadata(array('bootstrap' => 'index.php'));
        $phar->stopBuffering();
    }
    catch (Exception $e) {
        echo $e->getMessage();
    }
}

main();