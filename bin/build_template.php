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
 * 
 * 
 * @param string $name
 * @param string $content
 */
function wrap_in_function ($name, $content) {
    $name = substr($name, 0, strrpos($name, '.'));
    $name = str_replace('/', '_', $name);
    
    $function = '<?php function theme_%s (array $__data) { extract($__data); ?>%s<?php } ?>';
    
    return sprintf($function, $name, $content);
}

/**
 * Entry point
 * 
 * @param string $theme
 */
function main ($theme = 'default') {
    $basepath = dirname(__DIR__);
    
    $themes_directory = "$basepath/themes";
    $destination_directory = "$basepath/build/themes";
    
    if (!file_exists("$themes_directory/$theme")) {
        throw new Exception("Theme '$theme' doesn't exists!");
    }
    
    $files = glob_recursive("$themes_directory/$theme/*.php");
    $content = '';
    
    foreach ($files as $file) {
        $filename = str_replace("$themes_directory/$theme/", '', $file);
        $file_content = file_get_contents($file);
        
        if ($filename === 'functions.php') {
            $content = "{$file_content}?>{$content}";
        }
        else {
            $content .= wrap_in_function($filename, $file_content);
        }
    }
    
    $content = str_replace('?><?php', '', $content);
    
    file_put_contents("$destination_directory/$theme.php", $content);
}

if (isset($_SERVER['argv'][1])) {
    main($_SERVER['argv'][1]);
}
else {
    main();
}