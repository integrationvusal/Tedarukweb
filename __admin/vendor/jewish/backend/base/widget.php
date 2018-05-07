<?php

namespace jewish\backend\base;

use jewish\backend\base\controller;
use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class widget extends controller {
	public static $options;

	public static function init() {}

	public static function run() {
		return '';
	}

	public static function render($widget_view, $data=[]) {
		$tpl = view::render(WIDGET_DIR.$widget_view.'.php', $data);
		return $tpl;
	}
}

?>