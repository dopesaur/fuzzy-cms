<?php

/**
 * Check if current request is a post 
 * 
 * @return bool
 */
function is_post () {
    $method = array_get($_SERVER, 'REQUEST_METHOD', 'get');
    
    return strtolower($method) === 'post';
}