<?php

namespace app\controllers;

use app\helpers\app;
use app\models\videos;
use app\models\nav;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class videos_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_videos_list');

		$params = [];

		$page = intval(@$_GET['page']);
		videos::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = videos::getList();
		videos::setCategories($params['alldata'], nav::getAll());

		$params['count'] = videos::$items_amount;
		$params['total'] = videos::$pages_amount;
		$params['current'] = videos::$curr_pg;
		$params['limit'] = videos::$pp;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('videos', 'list');

		$params['canWrite'] = CMS::hasAccessTo('videos/list', 'read');

		return self::render('videos_list', $params);
	}

	public static function action_edit() { 
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_videos_edit');
		$_GET['id'] = abs((int)$_GET['id']);

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('videos/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('videos', 'list'): $_GET['return']);
		$params['data'] = videos::get($_GET['id']);

		$params['cats'] = nav::getCats();
		
		$params['selected_cats'] = 	explode(';',trim($params['data']['category'], ';'));

		if (isset($_POST['edit'])) {
			$params['op'] = videos::edit($_GET['id']);
			if ($params['op']['success']) {
				utils::redirect($params['link_back']);
			}
		}

		return self::render('videos_edit', $params);
	}

	public static function action_add() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_videos_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('videos/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('videos', 'list'): $_GET['return']);
		$params['cats'] = nav::getCats();

		if (isset($_POST['add'])) {
			$params['op'] = videos::add();
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('videos_add', $params);
	}

	public static function action_delete() {

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('videos/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('videos', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = videos::delete($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
		}
		else	return CMS::resolve('base/404');
	}

	public static function action_ajax_sort(){
		if(!utils::isAjax() || !CMS::hasAccessTo('videos/ajax_sort', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		header('Content-type: application/json; charset=utf-8');

		$response['message'] = 'Action is not registered.';

		if (isset($_POST['items']) && is_array($_POST['items'])) {
			$opts = videos::ajax_sort($_POST['items'], $_POST['cal_start']);
			$response['success'] = true;
			$response['message'] = 'Successfully sorted videos';
			if ($opts) 	$response['data'] = (int)$opts;
		}

		return json_encode($response);
	}
}

?>