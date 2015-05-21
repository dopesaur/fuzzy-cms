<?php

/**
 * Setting error reporting
 * and error display 
 */
error_reporting(-1);
ini_set('display_errors', 1);

$route = isset($_GET['route']) ? $_GET['route'] : '';

db_connect(BASEPATH . '/content/db.sqlite');

dispatch($route, array(
    'index_index',
    'post_view'
));