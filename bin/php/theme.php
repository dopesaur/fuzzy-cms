<?php

require 'shared.php';

/**
 * Wrap theme view into function
 * 
 * @param string $name
 * @param string $content
 * @param string $theme
 */
function wrap_in_function ($name, $content, $theme) {
    $name = substr($name, 0, strrpos($name, '.'));
    $name = str_replace('/', '_', $name);
    
    $function = '<?php function theme_%s_%s (array $__data) { ' .
                ' extract($__data); ?>%s<?php } ?>';
    
    return sprintf($function, $theme, $name, $content);
}

/**
 * Concatenate theme
 * 
 * @param array $files
 * @param string $directory
 * @param string $theme
 * @return string
 */
function concat_theme ($files, $directory, $theme) {
    $content = '';
    
    foreach ($files as $file) {
        $filename = str_replace("$directory/$theme/", '', $file);
        $file_content = file_get_contents($file);
        
        if ($filename === 'functions.php') {
            $content = "{$file_content}?>{$content}";
        }
        else {
            $content .= wrap_in_function($filename, $file_content, $theme);
        }
    }
    
    return str_replace('?><?php', '', $content);
}

/**
 * Entry point
 * 
 * @param string $theme
 */
function main ($theme = 'default') {
    $basepath = dirname(dirname(__DIR__));
    
    $themes_directory = "$basepath/themes";
    $destination_directory = "$basepath/build/themes";
    
    if (!file_exists("$themes_directory/$theme")) {
        throw new Exception("Theme '$theme' doesn't exists!");
    }
    
    $files = glob_recursive("$themes_directory/$theme/*.php");
    $content = concat_theme($files, $themes_directory, $theme);
    
    file_put_contents("$destination_directory/$theme.php", $content);
}

if (isset($_SERVER['argv'][1])) {
    main($_SERVER['argv'][1]);
}
else {
    main();
}