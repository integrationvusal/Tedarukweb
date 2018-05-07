<?php
defined('_VALID_PHP') or die('No direct script access.');

ini_set('session.cookie_httponly', true);
//ini_set('session.cookie_secure', true);
date_default_timezone_set('Asia/Baku');

require_once CONFIG_DIR.'db.php';
require_once CONFIG_DIR.'dirs.php';

define('CMS_ENV', 'dev');
if (@CMS_ENV!='dev') {
	error_reporting(0);
} else {
	error_reporting(E_ALL);
	ini_set('display_errors', true);
	ini_set('html_errors', true);
}
?>