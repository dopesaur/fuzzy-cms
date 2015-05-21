<?php

/**
 * Remove PHP tags
 * 
 * @param string $text
 * @return string
 */
function clean_tags ($text) {
    static $tags = array('<?php', '<?', '?>');
    static $replace = array('', '', '');
    
    return str_replace($tags, $replace, $text);
}

/**
 * Entry point, pass a config to build
 * 
 * @param string $json_config
 */
function main ($json_config) {
    $basepath = dirname(__DIR__) . '/';
    
    $build_config = file_get_contents($json_config);
    $build_config = json_decode($build_config, true);
    
    $files = $build_config['files'];
    
    $content = file_get_contents($basepath . array_shift($files));
    
    foreach ($files as $file) {
        $file_content = file_get_contents($basepath . $file);
        $file_content = clean_tags($file_content);
    
        $content .= $file_content;
    }
    
    file_put_contents($basepath . $build_config['destination'], $content);
}

/** Build core */
main(__DIR__ . '/build_core.json');