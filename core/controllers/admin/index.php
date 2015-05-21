<?php

/**
 * Admin index page
 */
function route_admin_index () {
    kick_out_user();
    
    theme('admin');
    
    layout('index', array(
        'title' => 'Howdy, admin!'
    ));
}

/**
 * Kick out unauthorized user
 */
function kick_out_user () {
    if (!is_admin()) {
        redirect('admin/login');
    }
}