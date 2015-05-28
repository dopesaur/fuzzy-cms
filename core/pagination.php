<?php

/**
 * Simple math function
 * Clamps a number ($int) between $max and $min
 * 
 * @param int $int
 * @param int $min
 * @param int $max
 */
function clamp ($int, $min, $max) {
    $int = max($int, $min);
    
    return min($int, $max);
}

/**
 * Create an array of pagination
 * 
 * @param int $total - Amount of items available
 * @param int $limit - Items per page
 * @param int $page  - Page number
 */
function pagination ($total, $limit, $page) {
    $page = (int)$page;
    
    $pages = ceil($total / $limit);
    $items = range(1, $pages);
    $page = clamp($page, 1, $pages);
    
    $offset = $limit * ($page - 1);
    
    return compact('pages', 'items', 'page', 'offset', 'limit');
}