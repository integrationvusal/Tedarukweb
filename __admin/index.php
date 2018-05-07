<?php

use jewish\backend\CMS;


define('_VALID_PHP', true);
if (isset($_GET['PHPSESSID'])) {
	header('HTTP/1.1 404 Not Found');
	die();
}

session_start();

define('CONFIG_DIR', 'app/config/');
require_once CONFIG_DIR.'app.php';
require_once CORE_DIR.'CMS.php';
spl_autoload_register(['jewish\backend\CMS', 'autoload']);
CMS::init();


header('Content-type: text/html; charset=utf-8');
header('X-Frame-Options: DENY');


//die(security::generatePasswordHash('admin', CMS::$salt));

if (empty($_SESSION[CMS::$sess_hash]['ses_adm_id']) && empty($_GET['controller'])) {

	print CMS::resolve('base', 'sign_in');
	die();
}

print CMS::resolve();

session_write_close();

?>