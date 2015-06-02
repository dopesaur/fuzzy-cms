<?php

/**
 * @const string BASEPATH Basepath
 */
define('FUZZY_START', microtime(true));
define('BASEPATH'   , chop(__DIR__, '/'));

/** Core includes */
require 'core/array.php';
require 'core/view.php';
require 'core/view_common.php';
require 'core/routing.php';
require 'core/db.php';
require 'core/auth.php';
require 'core/input.php';
require 'core/text.php';
require 'core/pagination.php';

/* Outsiders */
require 'core/vendor/Parsedown.php';
// require 'core/vendor/Spyc.php';

/** Controllers includes */
require 'core/controllers/index.php';
require 'core/controllers/admin/auth.php';
require 'core/controllers/admin/index.php';
require 'core/controllers/admin/posts.php';

/** Models includes */
require 'core/models/posts.php';

/** Bootsrap, kick it */
require 'core/bootstrap.php';

// Show me the execution time
printf('<!-- %.5f -->', microtime(true) - FUZZY_START);