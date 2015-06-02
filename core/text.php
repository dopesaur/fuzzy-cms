<?php

// Sorry guys
global $processors;

$processors = array();

/**
 * Add a processor
 * 
 * @param string $name
 * @param callable $processor
 */
function add_processor ($name, $processor) {
    // Sorry guys
    global $processors;
    
    $processors[$name] = $processor;
}

/**
 * Process config
 * 
 * @param string $name
 * @param string $config
 */
function process ($name, $config) {
    // Sorry guys
    global $processors;
    
    $processor = $processors[$name];
    
    return $processor($config);
}

/**
 * Process a file
 * 
 * @param string $file
 * @return array
 */
function process_file ($file) {
    $input = array();
    
    $content = capture(function () use ($file, &$input) {
        require $file;
        
        if (isset($data) && is_array($data)) {
            $input = $data;
        }
    });
    
    if (empty($input)) {
        list($input, $content) = process_content($content);
    }
    
    $input['content'] = markdown($content);
    
    return $input;
}

/**
 * Process content
 * 
 * @param string $content
 * @return array
 */
function process_content ($content) {
    $first  = strpos($content, '---');
    $second = strpos($content, '---', 1);
    
    if (
        $first !==  0 ||
        $second === -1
    ) {
        return array(array(), $content);
    }
    
    $config = trim(
        substr($content, $first + 3, $second - 3)
    );
    
    $newline = strpos($config, "\n");
    
    $processor = substr($config, 0, $newline);
    $config = substr($config, $newline + 1);
    
    return array(
        process($processor, $config),
        substr($content, $second + 3)
    );
}

/**
 * Process markdown via Parsedown
 * 
 * @param string $markdown
 */
function markdown ($markdown) {
    static $parse = null;
    
    // My® signature© move™
    $parse or $parse = new Parsedown;
    
    return $parse->text($markdown);
}

/**
 * Alias for Spyc YAML function
 * 
 * @param string $string
 * @return array
 */
function yaml ($string) {
    return spyc_load($string);
}