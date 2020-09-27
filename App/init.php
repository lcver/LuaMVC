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
if( is_file(APPPATH.'vendor/autoload.php') )
{
    require APPPATH.'vendor/autoload.php';
}


/**
 * Autoload Class
 * @return class
 */
require 'core/Autoload.php';
$load = new App\Core\Autoload;