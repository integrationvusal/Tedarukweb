<?php

namespace app\controllers;

use app\helpers\app;
use app\models\wholesale;
use app\models\measure;
use app\models\product;
use app\models\markets;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class wholesale_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_wholesale_list');

		$params = [];

		$page = intval(@$_GET['page']);
		wholesale::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = wholesale::getList();
		$params['count'] = wholesale::$items_amount;
		$params['total'] = wholesale::$pages_amount;
		$params['current'] = wholesale::$curr_pg;
		$params['limit'] = wholesale::$pp;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('wholesale', 'list');

		$params['canWrite'] = CMS::hasAccessTo('wholesale/list', 'read');

		return self::render('wholesale_list', $params);
	}
	
	public static function action_add() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_wholesale_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('wholesale/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('wholesale', 'list'): $_GET['return']);

		$params['langs'] = CMS::$site_langs;
		$params['measure'] = measure::getList();
		$params['product'] = product::getList();
		$params['markets'] = markets::getList();
		

		if (isset($_POST['add'])) {
			$params['op'] = wholesale::add();
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('wholesale_add', $params);
	}

	public static function action_edit() { 
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_wholesale_edit');
		$_GET['id'] = abs((int)$_GET['id']);

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('wholesale/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('wholesale', 'list'): $_GET['return']);
		$params['data'] = wholesale::get($_GET['id']);
		$params['measure'] = measure::getList();
		$params['product'] = product::getList();
		$params['markets'] = markets::getList();

		if (isset($_POST['edit'])) {
			$params['op'] = wholesale::edit($_GET['id']);
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('wholesale_edit', $params);
	}
	
		public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('wholesale/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('wholesale', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = wholesale::delete($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::redirect($params['link_back']);
		}
		else	return CMS::resolve('base/404');
	}


	public static function action_ajax_sort(){
		if(!utils::isAjax() || !CMS::hasAccessTo('wholesale/ajax_sort', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$opts = wholesale::ajax_sort($_POST['items'], $_POST['cal_start']);
			$response['success'] = true;
			$response['message'] = 'Successfully sorted wholesale';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}
}

?>