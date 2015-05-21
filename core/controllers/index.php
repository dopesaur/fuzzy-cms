<?php

/**
 * Index page
 */
function index_index () {
    layout('posts/index', array(
        'title' => 'All posts',
        'posts' => posts_all()
    ));
}

function post_view ($post_id) {
    $post = post_by_id($post_id);
    
    layout('posts/post', array(
        'title' => 'Post ' . $post['title'],
        'post'  => $post,
    ));
}