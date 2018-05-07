<?php

namespace app\controllers;

use app\helpers\app;
use jewish\backend\base\widget;
use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class breadcrumb_widget_controller extends widget {

	public static function run() {
		return self::render('breadcrumbs', [
			'links' => self::$options['links']
		]);
	}
}

?>