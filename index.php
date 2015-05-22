<?php

/**
 * @const string BASEPATH Basepath
 */
define('BASEPATH', chop(__DIR__, '/'));

/** Core includes */
require 'core/array.php';
require 'core/view.php';
require 'core/view_common.php';
require 'core/routing.php';
require 'core/db.php';
require 'core/auth.php';
require 'core/input.php';

/** Controllers includes */
require 'core/controllers/index.php';
require 'core/controllers/admin/auth.php';
require 'core/controllers/admin/index.php';
require 'core/controllers/admin/posts.php';

/** Models includes */
require 'core/models/posts.php';

/** Theme functions includes, just for now */
require 'themes/default/functions.php';

/** Bootsrap, kick it */
require 'core/bootstrap.php';