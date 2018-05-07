<?php
namespace jewish\backend\base;

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class controller {
	public static $layout = '';

	public static function render($action_view, $data=[]) {
		$tpl = view::render(TPL_DIR.$action_view.'.php', $data);
		if (!empty(self::$layout)) {
			$tpl = view::render(VIEW_DIR.self::$layout.'.php', [
				'content' => $tpl
			]);
		}
		return $tpl;
	}

	protected static function checkRequestArray($allData, $int = false){
		$cleanedData = [];

		foreach($allData as $key=>$data){
			if(!empty(trim($data)))
				$cleanedData[$key] = $int?abs((int)$data):$data;
		}

		return $cleanedData;

	}
}

?>