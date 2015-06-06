<?php

/**
 * Setting error reporting
 * and error display 
 */
error_reporting(-1);
ini_set('display_errors', 1);

date_default_timezone_set(config('general.timezone', 'Europe/London'));

session_start();

load_extensions();

auth_user(
    array_get($_SESSION, 'username'),
    array_get($_SESSION, 'password')
);

theme(config('general.theme', 'default'));

db_connect(basepath('content/db.sqlite'));

$route = array_get($_GET, 'route', '');

if (!route_content($route)) {
    dispatch($route);
}