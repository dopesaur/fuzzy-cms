<?php

/**
 * Check whether current user is admin
 *
 * @param bool $authorized
 * @return bool
 */
function is_admin ($authorized = null) {
    static $admin = false;
    
    if ($authorized !== null) {
        $admin = $authorized;
    }
    
    return $admin === true;
}

/**
 * Authorize the user
 * 
 * @param string $username
 * @param string $password
 * @return bool
 */
function auth_user ($username, $password) {
    $user = config('users.admin');
    
    return is_admin(
        $username === $user['username'] && 
        $password === $user['password']
    );
}