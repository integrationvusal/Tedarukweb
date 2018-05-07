<?php

namespace app\controllers;

use app\helpers\app;
use app\models\actual_links;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class actual_links_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_documents_type_list');

		$params = [];

		$page = intval(@$_GET['page']);
		actual_links::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = actual_links::getList();
		$params['count'] = actual_links::$items_amount;
		$params['total'] = actual_links::$pages_amount;
		$params['current'] = actual_links::$curr_pg;
		$params['limit'] = actual_links::$pp;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('actual_links', 'list');

		$params['canWrite'] = CMS::hasAccessTo('actual_links/list', 'read');

		return self::render('actual_links_list', $params);
	}

	public static function action_add() { // 2016-09-13
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_actual_links_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('actual_links/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('actual_links', 'list'): $_GET['return']);

		if (isset($_POST['add'])) {
			$params['op'] = actual_links::add();
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('actual_links_add', $params);
	}

	public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('actual_links/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('actual_links', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = actual_links::delete($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
		}
		else	return CMS::resolve('base/404');
	}


	public static function action_ajax_sort(){
		if(!utils::isAjax() || !CMS::hasAccessTo('actual_links/ajax_sort', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$opts = actual_links::ajax_sort($_POST['items'], $_POST['cal_start']);
			$response['success'] = true;
			$response['message'] = 'Successfully sorted actual links';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}

}

?>