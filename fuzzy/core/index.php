<?php

/**
 * Load the core
 */
function load_core () {
    /** Core includes */
    require 'array.php';
    require 'storage.php';
    require 'config.php';
    require 'content.php';
    require 'view.php';
    require 'view_common.php';
    require 'routing.php';
    require 'db.php';
    require 'auth.php';
    require 'input.php';
    require 'text.php';
    require 'pagination.php';
    require 'extensions.php';
    
    $basepath = chop(__DIR__, '/');
    $basepath = dirname($basepath);
    
    /** Controllers includes */
    require "$basepath/controllers/index.php";
    require "$basepath/controllers/admin/auth.php";
    require "$basepath/controllers/admin/index.php";
    require "$basepath/controllers/admin/posts.php";
    
    /** Models includes */
    require "$basepath/models/posts.php";
}