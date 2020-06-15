<?php
/**
 * Base URL
 * @return URL
 */
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https' : 'http';
$base_url.= "://".$_SERVER['HTTP_HOST'];
$base_url.= str_replace(basename($_SERVER['SCRIPT_NAME']),'',$_SERVER['SCRIPT_NAME']);
define('BASEURL', $base_url);

/**
 * Start Session
 * 
 * @start Session
 */
session_start();

/**
 * APP PATH
 * Directories of app folder.
 * 
 * @return Directory
 */
$app_path = realpath(dirname(__FILE__));
$app_path = str_replace("\\",'/',$app_path);
// $app_path = str_replace($_SERVER['DOCUMENT_ROOT'],'',$app_path);
$app_path = preg_replace('/config/', '', $app_path);
$app_path = str_replace("App/",'',$app_path);
define('APPPATH',$app_path);

/**
 * PATH MVC
 * Directories of Core, Model, View, Controller.
 * 
 * @return Directory
 */
define('CORE_PATH', APPPATH.'App/core/');
define('MPATH', APPPATH.'App/models/');
define('VPATH', APPPATH.'App/views/');
define('CPATH', APPPATH.'App/controllers/');
define('LPATH', APPPATH.'App/library/');

/**
 * DATABASE
 * Configuration Database
 * 
 * @return Constant
 */
define('DBHOST','localhost');
define('DBUSER','root');
define('DBPASS','Lucver1092');
define('DBNAME','mvc');


/**
 * Vendor all path
 * Const directories
 * 
 * @return Directory
 */
define('VNDRPATH',APPPATH.'vendor/');