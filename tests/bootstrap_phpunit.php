<?php
/**
 * This file is executed before every run of the tests
 */

// Avoid ellipsis of xdebug dumps
ini_set('xdebug.max_nesting_level', 10000);
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
