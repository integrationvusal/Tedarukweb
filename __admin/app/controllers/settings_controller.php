<?php

namespace app\controllers;

use app\helpers\app;
use app\models\settings;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class settings_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_settings_list');

		$params = [];

		$page = intval(@$_GET['page']);
		settings::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = settings::getList();

		$params['count'] = settings::$items_amount;
		$params['total'] = settings::$pages_amount;
		$params['current'] = settings::$curr_pg;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('settings', 'list');

		$params['canWrite'] = CMS::hasAccessTo('settings/list', 'read');

		return self::render('settings_list', $params);
	}

	public static function action_edit() { // 2016-09-13
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_settings_edit');
		$_GET['id'] = abs((int)$_GET['id']);

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('settings/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('settings', 'list'): $_GET['return']);
		$params['data'] = settings::get($_GET['id']);

		if (isset($_POST['edit'])) {
			$params['op'] = settings::edit($_GET['id']);
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('settings_edit', $params);
	}

	public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('settings/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('settings', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = settings::deleteDocumentType($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
		}
		else	return CMS::resolve('base/404');
	}

}

?>