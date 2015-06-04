<?php

/**
 * @const float  FUZZY_START Start time
 * @const string BASEPATH Basepath
 */
define('FUZZY_START', microtime(true));
define('BASEPATH'   , chop(__DIR__, '/'));

/** Core includes */
require 'fuzzy/core/array.php';
require 'fuzzy/core/view.php';
require 'fuzzy/core/view_common.php';
require 'fuzzy/core/routing.php';
require 'fuzzy/core/db.php';
require 'fuzzy/core/auth.php';
require 'fuzzy/core/input.php';
require 'fuzzy/core/text.php';
require 'fuzzy/core/pagination.php';

/* Outsiders */
require 'fuzzy/vendor/Parsedown.php';
require 'fuzzy/vendor/Spyc.php';

/** Controllers includes */
require 'fuzzy/controllers/index.php';
require 'fuzzy/controllers/admin/auth.php';
require 'fuzzy/controllers/admin/index.php';
require 'fuzzy/controllers/admin/posts.php';

/** Models includes */
require 'fuzzy/models/posts.php';

/** Bootsrap, kick it */
require 'fuzzy/bootstrap.php';

// Show me the execution time
printf('<!-- %.5f -->', microtime(true) - FUZZY_START);