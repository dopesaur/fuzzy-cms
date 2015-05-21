<?php

define('BASEPATH', chop(__DIR__, '/'));

/**
 * Core
 * 
 * 
 */
require 'core/array.php';
require 'core/view.php';
require 'core/view_common.php';
require 'core/routing.php';
require 'core/db.php';
require 'core/auth.php';
require 'core/input.php';

require 'core/controllers/index.php';
require 'core/controllers/admin/auth.php';
require 'core/controllers/admin/index.php';
require 'core/controllers/admin/posts.php';

require 'core/models/posts.php';

require 'themes/default/functions.php';

require 'core/bootstrap.php';