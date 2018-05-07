<?php

namespace app\controllers;

use app\helpers\app;
use app\models\measure;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class measure_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_measure_list');

		$params = [];

		$page = intval(@$_GET['page']);
		measure::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = measure::getList();
		$params['count'] = measure::$items_amount;
		$params['total'] = measure::$pages_amount;
		$params['current'] = measure::$curr_pg;
		$params['limit'] = measure::$pp;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('measure', 'list');

		$params['canWrite'] = CMS::hasAccessTo('measure/list', 'read');

		return self::render('measure_list', $params);
	}
	
	public static function action_add() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_measure_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('measure/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('measure', 'list'): $_GET['return']);

		$params['langs'] = CMS::$site_langs;

		if (isset($_POST['add'])) {
			$params['op'] = measure::add();
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('measure_add', $params);
	}

	public static function action_edit() { 
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_measure_edit');
		$_GET['id'] = abs((int)$_GET['id']);

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('measure/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('measure', 'list'): $_GET['return']);
		$params['data'] = measure::get($_GET['id']);

		if (isset($_POST['edit'])) {
			$params['op'] = measure::edit($_GET['id']);
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('measure_edit', $params);
	}
	
		public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('measure/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('measure', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = measure::delete($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::redirect($params['link_back']);
		}
		else	return CMS::resolve('base/404');
	}


	public static function action_ajax_sort(){
		if(!utils::isAjax() || !CMS::hasAccessTo('measure/ajax_sort', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$opts = measure::ajax_sort($_POST['items'], $_POST['cal_start']);
			$response['success'] = true;
			$response['message'] = 'Successfully sorted measure';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}
}

?>