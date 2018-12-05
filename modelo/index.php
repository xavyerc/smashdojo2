<?php
/**
 * CodeDmx
 *
 * An open source application development framework for PHP
 *
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 - 2016
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @package	CodeDmx
 * @author	LOverride
 * @copyright	Copyright (c) 2014 - 2016, Code Dmx (http://codedmx.com/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codedmx.com
 * @since	Version 1.0
 * @filesource
 */
$elapsed_time = microtime(true);
/*
 * ---------------------------------------------------------------
 *  REMOVE CACHE SYSTEM
 * ---------------------------------------------------------------
 */
// opcache_reset();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
header("Cache-Control: no-store, no-cache, must-revalidate"); 
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

/*
 * ---------------------------------------------------------------
 *  USE UTF-8 FOR ALL MULTIBYTE FUNCTIONS
 * ---------------------------------------------------------------
 */
if (extension_loaded('mbstring'))
{
    mb_internal_encoding('UTF-8');
    mb_http_output('UTF-8');
}

/*
 * ---------------------------------------------------------------
 *  COMPARE PHP VERSION
 * ---------------------------------------------------------------
 *
 * Compare PHP version of your current develop.
 */
if (version_compare(PHP_VERSION, '7.0.7', '<')) 
{
    include dirname(__FILE__) . '/View/static/Version.php';
    exit(1);
}

/*
 * ---------------------------------------------------------------
 *  HANDLE PHP WEBSERVER
 * ---------------------------------------------------------------
 */
if (PHP_SAPI === 'cli-server') 
{
    if (is_file($_SERVER['DOCUMENT_ROOT'] . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']))) 
    {
        return false;
    }
}

/**
 * ---------------------------------------------------------------
 *  ERROR REPORTING
 * ---------------------------------------------------------------
 *
 * You can display erros depending on your current develop.
 *
 * This can be set to anything, but default usage is:
 * @var bool
 */
$dev = TRUE;
switch ($dev)
{
	// Show all errors
	case TRUE:
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
	break;
	
	// Hide all errors
	case FALSE:
		ini_set('display_erros', 0);
		error_reporting(0);
	break;
	
	default:
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'The application environment is not set correctly.';
		exit(1);
	break;
}

/*
 * ---------------------------------------------------------------
 *  LOAD CONFIG FILE
 * ---------------------------------------------------------------
 *
 * Load all global variables to use on the site.
 */
require_once dirname(__FILE__) . '/Model/CFG.php';

/*
 * ---------------------------------------------------------------
 *  LOAD ALL THE CLASSES
 * ---------------------------------------------------------------
 *
 * Load all global variables to use on the site.
 */
require_once dirname(__FILE__) . '/Model/Loader.php';
$cod = new COD_Loader;

// --------------------------------------------------------------------
//  RUN THE SITE
// --------------------------------------------------------------------
$cod->run($elapsed_time);
?>