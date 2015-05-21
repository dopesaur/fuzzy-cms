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