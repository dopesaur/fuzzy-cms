<?php

/**
 * Index page
 */
function route_index_index () {
    route_posts_view();
}

/**
 * Index page
 * 
 * @param string $page
 */
function route_posts_view ($page = 1) {
    if (!$page) {
        not_found();
    }
    
    $posts = posts_all_paginated(config('blog.posts', 5), $page);
    
    layout('posts/index', array(
        'title'      => 'All posts',
        'posts'      => $posts['posts'],
        'pagination' => $posts['pagination']
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
        'title' => "Post {$post['title']}",
        'post'  => $post,
    ));
}