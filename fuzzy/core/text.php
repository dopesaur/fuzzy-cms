<?php

/**
 * Processors storage
 * 
 * @param string $key
 * @param mixed $value
 * @return mixed
 */
function processors ($key = null, $value = null) {
    static $storage = null;
    
    $storage or $storage = storage();
    
    return $storage($key, $value);
}

/**
 * Process config
 * 
 * @param string $name
 * @param string $config
 */
function process ($name, $config) {
    $processor = processors($name);
    
    if (!is_callable($processor)) {
        throw new InvalidArgumentException("Processor '$name' doesn't exists!");
    }
    
    return $processor($config);
}

/**
 * Process a file
 * 
 * @todo decompose
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
        list($input, $content) = process_config($content);
    }
    
    $processor = array_get($input, 'processor');
    $processor = $processor ? $processor : config('general.processing.content');
    
    $input['content'] = $processor ? process($processor, $content) : $config;
    
    return $input;
}

/**
 * Process content
 * 
 * @todo decompose
 * @param string $content
 * @return array
 */
function process_config ($content) {
    $first  = strpos($content, '---');
    $second = strpos($content, '---', 1);
    
    if ($first !== 0 || $second === -1) {
        return array(array(), $content);
    }
    
    $config = substr($content, $first + 3, $second - 3);
    
    $newline = strpos($config, "\n");
    
    $processor = trim(substr($config, 0, $newline));
    $processor = $processor ? $processor : config('general.processing.header');
    
    $config = substr($config, $newline);
    
    return array(
        process($processor, $config),
        substr($content, $second + 3)
    );
}