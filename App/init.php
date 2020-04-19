<?php
/**
 * Configuration
 * 
 * @return PATH
 * MPATH, VPATH, CPATH, LPATH
 */
require 'config/config.php';

/**
 * Autoload Vendor
 * @return vendor
 */
require APPPATH.'vendor/autoload.php';

/**
 * Autoload Class
 * @return class
 */
require 'core/Autoload.php';
$load = new App\Core\Autoload;