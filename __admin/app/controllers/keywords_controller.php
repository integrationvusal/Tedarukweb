<?php

namespace app\controllers;

use app\helpers\app;
use app\models\keywords;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class keywords_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_keywords_list');

		$params = [];

		$page = intval(@$_GET['page']);
		keywords::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = keywords::getKeywordsList();
		$params['count'] = keywords::$items_amount;
		$params['total'] = keywords::$pages_amount;
		$params['current'] = keywords::$curr_pg;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('keywords', 'list');

		$params['canWrite'] = CMS::hasAccessTo('keywords/list', 'read');

		return self::render('keywords_list', $params);
	}

	public static function action_add() { // 2016-09-13
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_keywords_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('keywords/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('keywords', 'list'): $_GET['return']);

		if (isset($_POST['add'])) {
			$params['op'] = keywords::addKeyword();
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('keywords_add', $params);
	}

	public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('keywords/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('keywords', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = keywords::deleteKeyword($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
		}
		else	return CMS::resolve('base/404');
	}

	public static function action_add_by_value(){
		if(!utils::isAjax() || !CMS::hasAccessTo('keywords/add_by_value', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		$_GET['value'] = utils::strClear($_GET['value']);

		if ($_GET['value']) {
			$opts = keywords::addKeywordByValue($_GET['value']);
			$response['success'] = true;
			$response['message'] = 'Successfully added new keyword by value';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}
}

?>