<?php

/**
 * Index page
 */
function route_index_index () {
    layout('posts/index', array(
        'title' => 'All posts',
        'posts' => posts_all()
    ));
}

/**
 * View a post
 * 
 * @param string $post_id
 */
function route_post_view ($post_id = 0) {
    $post = post_by_id($post_id);
    
    if (empty($post)) {
        not_found();
    }
    
    layout('posts/post', array(
        'title' => 'Post ' . $post['title'],
        'post'  => $post,
    ));
}