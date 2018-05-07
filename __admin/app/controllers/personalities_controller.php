<?php

namespace app\controllers;

use app\helpers\app;
use app\models\personalities;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class personalities_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_personalities_list');

		$params = [];

		$page = intval(@$_GET['page']);
		personalities::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = personalities::getList();
		$params['count'] = personalities::$items_amount;
		$params['total'] = personalities::$pages_amount;
		$params['current'] = personalities::$curr_pg;
		$params['limit'] = personalities::$pp;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('personalities', 'list');

		$params['canWrite'] = CMS::hasAccessTo('personalities/list', 'read');

		return self::render('personalities_list', $params);
	}

	public static function action_add() { // 2017-01-20
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_personalities_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('personalities/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? '?controller=personalities&action=list': $_GET['return']);

		$params['langs'] = CMS::$site_langs;

		if (isset($_POST['add'])) {
			$params['op'] = personalities::add();
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('personalities_add', $params);
	}

	public static function action_edit() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_personalities_edit');
		$_GET['id'] = abs((int)$_GET['id']);

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('personalities/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('personalities', 'list'): $_GET['return']);
		$params['data'] = personalities::get($_GET['id']);

		if (isset($_POST['edit'])) {
			$params['op'] = personalities::edit($_GET['id']);
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('personalities_edit', $params);
	}

	public static function action_delete() {

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('personalities/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('personalities', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = personalities::delete($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::redirect($params['link_back']);
		}
		else	return CMS::resolve('base/404');
	}


	public static function action_ajax_sort(){
		if(!utils::isAjax() || !CMS::hasAccessTo('personalities/ajax_sort', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$opts = personalities::ajax_sort($_POST['items'], $_POST['cal_start']);
			$response['success'] = true;
			$response['message'] = 'Successfully sorted personalities';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}

}

?>