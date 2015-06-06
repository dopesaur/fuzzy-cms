<?php 

/**
 * Get a key from multidimensional array if is set
 * 
 * @param array $array
 * @param string $key
 * @param mixed $default
 */
function array_get (array $array, $key, $default = false) {
    if (isset($array[$key])) {
        return $array[$key];
    }
    
    $keys = explode('.', $key);
    $key = array_shift($keys);
    
    while ($key !== null && isset($array[$key])) {
        $array = $array[$key];
        
        $key = array_shift($keys);
    }
    
    if ($array !== null && $key === null) {
        return $array;
    }
    
    return $default;
}

/**
 * Set a value in array
 * 
 * @todo make this function able to do cool sutff as array_get
 *       can do
 * @param array $array
 * @param string $key
 * @param mixed $value
 */
function array_set (array &$array, $key, $value) {
    $array[$key] = $value;
}