<?php

/**
 * Check whether current user is admin
 *
 * @param bool $authorized
 * @return bool
 */
function is_admin ($authorized = false) {
    static $admin = false;
    
    if ($authorized) {
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
    if (
        $username === config('users.username') && 
        $password === config('users.password')
    ) {
        return is_admin(true);
    }
    
    return false;
}