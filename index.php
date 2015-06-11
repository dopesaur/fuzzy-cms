<?php

/**
 * Development version (uncompressed) of Fuzzy CMS
 * 
 * @package Fuzzy
 */

/**
 * @const float  FUZZY_START Start time
 * @const string BASEPATH Basepath
 */
define('FUZZY_START', microtime(true));
define('BASEPATH'   , chop(__DIR__, '/'));

/** Require core file */
require 'fuzzy/core/index.php';

load_core();

/** Bootsrap, kick it */
require 'fuzzy/bootstrap.php';