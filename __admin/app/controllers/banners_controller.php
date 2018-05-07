<?php

namespace app\controllers;

use app\helpers\app;
use app\models\banners;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class banners_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_banners_list');

		$params = [];

		$page = intval(@$_GET['page']);
		banners::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = banners::getList();
		$params['count'] = banners::$items_amount;
		$params['total'] = banners::$pages_amount;
		$params['current'] = banners::$curr_pg;
		$params['limit'] = banners::$pp;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('banners', 'list');

		$params['canWrite'] = CMS::hasAccessTo('banners/list', 'read');

		return self::render('banners_list', $params);
	}

	public static function action_add() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_banners_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('banners/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('banners', 'list'): $_GET['return']);

		if (isset($_POST['add'])) {
			$params['op'] = banners::add();
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('banners_add', $params);
	}

	public static function action_edit() { // 2016-09-13
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_banners_edit');
		$_GET['id'] = abs((int)$_GET['id']);

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('banners/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('banners', 'list'): $_GET['return']);
		$params['data'] = banners::get($_GET['id']);

		if (isset($_POST['edit'])) {
			$params['op'] = banners::edit($_GET['id']);
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('banners_edit', $params);
	}

	public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('banners/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('banners', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = banners::delete($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
		}
		else	return CMS::resolve('base/404');
	}
	
	
	public static function action_ajax_sort(){
		if(!utils::isAjax() || !CMS::hasAccessTo('banners/ajax_sort', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$opts = banners::ajax_sort($_POST['items'], $_POST['cal_start']);
			$response['success'] = true;
			$response['message'] = 'Successfully sorted banners';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}

}

?>