<?php
/**
 * Base URL
 * @return URL
 */
define('BASEURL','http://localhost/belajar/MVC/public/');

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
define('APPPATH',__DIR__.'/../');

/**
 * PATH MVC
 * Directories of Core, Model, View, Controller.
 * 
 * @return Directory
 */
define('CORE_PATH', __DIR__.'/../core/');
define('MPATH', __DIR__.'/../model/');
define('VPATH', __DIR__.'/../view/');
define('CPATH', __DIR__.'/../controllers/');
define('LPATH', __DIR__.'/../library/');