<?php
	defined('_VALID_PHP') or die('No direct script access.');
	define('SITE', 'http://'.$_SERVER['HTTP_HOST'].'/');
	define('SITE_DIR', '/');
	define('CMS_DIR', '__admin/');
	define('UPLOADS_DIR', '../upload/');
	define('APP_DIR', 'app/');
	define('LANG_DIR', APP_DIR.'langs/');
	define('MODEL_DIR', APP_DIR.'models/');
	define('CONTROLLER_DIR', APP_DIR.'controllers/');
	define('VIEW_DIR', APP_DIR.'views/');
	define('TPL_DIR', APP_DIR.'views/actions/');
	define('WIDGET_DIR', APP_DIR.'views/widgets/');
	define('VENDOR_DIR', 'vendor/');
	define('CORE_DIR', 'vendor/jewish/backend/');
	define('HELPER_DIR', CORE_DIR.'helpers/');
	define('WEB_DIR', 'web/');
	define('CSS_DIR', WEB_DIR.'css/');
	define('JS_DIR', WEB_DIR.'js/');
	define('IMAGE_DIR', WEB_DIR.'images/');
?>