<?php

/**
 * Get all posts
 * 
 * @return array
 */
function posts_all () {
    return db_select('SELECT id, date, title, content FROM posts ORDER BY id DESC');
}

/**
 * Get all posts paginated
 * 
 * @param int $limit
 * @parma int $page
 * @return array
 */
function posts_all_paginated ($limit, $page = 1) {
    $total = posts_count();
    
    $pagination = pagination($total, $limit, $page);
    
    // Maybe I should use extract?
    $limit = $pagination['limit'];
    $offset = $pagination['offset'];
    
    $posts = db_select(
        'SELECT id, date, title, content FROM posts ORDER BY id DESC LIMIT ? OFFSET ?',
        array($limit, $offset)
    );
    
    return compact('posts', 'pagination');
}

/**
 * Count all posts
 * 
 * @return string
 */
function posts_count () {
    $count = db_select('SELECT COUNT(*) FROM posts', array(), true);
    
    return current($count);
}

/**
 * Get a post by id
 * 
 * @param string|int $id
 * @return array
 */
function post_by_id ($id) {
    return db_select(
        'SELECT id, date, title, content FROM posts WHERE id = ?', 
        array($id), true
    );
}