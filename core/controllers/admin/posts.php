<?php

/**
 * View all posts
 */
function route_admin_posts_view () {
    theme('admin');
    
    layout('posts/view', array(
        'title' => 'View posts',
        'posts' => db_browse('posts')
    ));
}

/**
 * Posts form
 * 
 * @return array
 */
function posts_form () {
    return array(
        'title' => array(
            'type' => 'input'
        ),
        
        'content' => array(
            'type' => 'text'
        ),
        
        'description' => array(
            'type' => 'input'
        )
    );
}

/**
 * Display post creation form
 */
function route_admin_posts_add () {
    if (is_post() && admin_posts_add($_POST)) {
        redirect('admin/posts-view');
    }
    
    theme('admin');
    
    layout('posts/modify', array(
        'title'  => 'View posts',
        'action' => 'add',
        
        'form' => posts_form()
    ));
}

/**
 * Add a post
 * 
 * @param array $input
 */
function admin_posts_add (array $input) {
    return db_insert('posts', $input);
}

/**
 * Display post editing form
 * 
 * @param string $id
 */
function route_admin_posts_edit ($id = 0) {
    if (is_post() && admin_posts_edit($id, $_POST)) {
        redirect('admin/posts-view');
    }
    
    theme('admin');
    
    $post = db_find('posts', 'title, content, description', $id);
    
     if (!$post) {
        not_found();
    }
    
    $form = posts_form();
    
    foreach ($post as $key => $value) {
        $form[$key]['value'] = $value;
    }
    
    layout('posts/modify', array(
        'title'  => 'View posts',
        'action' => 'edit',
        
        'form' => $form
    ));
}

/**
 * Add a post
 * 
 * @param string $id
 * @param array $input
 */
function admin_posts_edit ($id, array $input) {
    return db_update('posts', $input, $id) > 0;
}

/**
 * Remove a post
 * 
 * @param string $id
 */
function route_admin_posts_remove ($id = 0) {
    if (!$id) {
        not_found();
    }
    
    db_query('DELETE FROM posts WHERE id = ?', array($id));
    
    redirect('admin/posts-view');
}