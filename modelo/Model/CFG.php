<?php
/*
|--------------------------------------------------------------------------
| DEFINE DIRECTORY SEPARATOR
|--------------------------------------------------------------------------
|
*/
define('DS', DIRECTORY_SEPARATOR);

/*
|--------------------------------------------------------------------------
| SET $COD (codedmx) VARIABLE TO USE
|--------------------------------------------------------------------------
|
*/
unset($COD);
global $COD;
$COD = new stdClass;

/*
|--------------------------------------------------------------------------
| BASE SITE URL
|--------------------------------------------------------------------------
|
| URL to your LocalGit project.
| Can set manually like the URL on is host your project, but if you want
| you can change with a real URL
|
|	http://codedmx.com/
|
| WARNING: If you change this, it will get errors
|
*/
$GLOBALS['COD']->dir = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ).'://'.$_SERVER['HTTP_HOST'].DS."modelo";

/*
|--------------------------------------------------------------------------
| BASE SITE DOCUMENT ROOT
|--------------------------------------------------------------------------
|
| Is about Document Root $_SERVER['DOCUMENT_ROOT'] of your project.
|
|	/var/www/vhosts/codedmx/
|
| This set manually.
|
*/
$GLOBALS['COD']->doc = str_replace("Model", "", realpath(dirname(__FILE__)));

/*
|--------------------------------------------------------------------------
| SET YOUR GOOGLE ANALYTICS ACCOUNT
|--------------------------------------------------------------------------
|
*/
$GLOBALS['COD']->analytics = 'UA-XXXXXXX-X';

/*
| -------------------------------------------------------------------
| DATABASE SETTINGS
| -------------------------------------------------------------------
| This variables are to access your database.
|
| If you don't need database connection just change host:
|
|	$GLOBALS['COD']->host = null;
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['host'] The hostname of your database server.
|	['usr'] The username used to connect to the database
|	['pwd'] The password used to connect to the database
|	['db'] The name of the database you want to connect to
|	['port'] The port used to connect to the database
|	['charset'] The character set used in communicating with the database
|
*/
//$GLOBALS['COD']->host = 'localhost';
$GLOBALS['COD']->host = null;
$GLOBALS['COD']->db = '';
$GLOBALS['COD']->usr = '';
$GLOBALS['COD']->pwd = '';
$GLOBALS['COD']->port = 3306;
$GLOBALS['COD']->charset = 'utf8';

/*
| -------------------------------------------------------------------
| SCHEMA OF PARAMETER TO HASH THE PASSWORDS
| -------------------------------------------------------------------
|
| Can be: 2a, 2x or 2y
|
*/
$GLOBALS['COD']->identifier = '2y';
?>