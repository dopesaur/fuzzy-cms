<?php

/**
 * Show login form
 * 
 * @param string $error
 */
function route_admin_login ($error = '') {
    theme('admin');
    
    view('auth', array(
        'title' => 'Log in, user!',
        'error' => $error
    ));
}

/**
 * Process authorization
 */
function route_admin_login_post () {
    $username = array_get($_POST, 'username');
    $password = md5(array_get($_POST, 'password'));
    
    if (auth_user($username, $password)) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        
        redirect('admin');
    }
    
    route_admin_login('Wrong username or password!');
}

/**
 * Log out admin
 */
function route_admin_logout () {
    session_destroy();
    
    redirect('');
}