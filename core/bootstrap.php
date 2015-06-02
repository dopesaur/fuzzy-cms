<?php

/**
 * Setting error reporting
 * and error display 
 */
error_reporting(-1);
ini_set('display_errors', 1);

date_default_timezone_set('America/Los_Angeles');

session_start();

add_processor('json', function ($config) { 
    return json_decode($config, true); 
});

add_processor('yaml', function ($config) {
    return yaml($config); 
});

auth_user(
    array_get($_SESSION, 'username'),
    array_get($_SESSION, 'password')
);

db_connect(BASEPATH . '/content/db.sqlite');

dispatch(array_get($_GET, 'route', ''));