<?php

namespace app\controllers;

use app\helpers\app;
use app\models\nav;
use app\models\articles;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class nav_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_nav_list');

		$params = [];

		$page = intval(@$_GET['page']);
		$_GET['item'] = isset($_GET['item'])?abs((int)$_GET['item']):0;
		nav::$curr_pg = (empty($page)? 1: $page);


		if($_GET['item']){
			$params['langs'] = CMS::getLangsRegistered();
			$params['item'] = nav::getNavItem($_GET['item']);
			$params['navTypes'] = nav::$types;

			$params['articles'] = articles::getArticlesList(false);

			$params['canWrite'] = CMS::hasAccessTo('nav/list', 'write');

			if ( (isset($_POST['save']) || isset($_POST['apply'])) && $params['canWrite']) {
				$response = nav::editNavItem($_GET['item']);
				if (!$response['success'] && !empty($response['errors'])) {
					$params['notice']['errors'] = $response['errors'];
				} else if ($response['success']) {
					$params['notice']['message'] = $response['message'];
					if (isset($_POST['save'])) {
						utils::delayedRedirect(view::create_url('nav', 'list', ['item' => $_GET['item']]), 0);
					}
				}
			}
		}

		$params['menu'] = nav::getMenu();
		$params['positions'] = nav::getPositions();
		$params['count'] = nav::$items_amount;
		$params['total'] = nav::$pages_amount;
		$params['current'] = nav::$curr_pg;
		$params['navAdd'] = false;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = urlencode(SITE.CMS_DIR.utils::trueLink(['controller', 'action', 'q', 'page']));

		$params['navTree'] = nav::showNavTree($params['menu'], $params['current'], utils::trueLink(['controller', 'action']));

		$params['canWrite'] = CMS::hasAccessTo('nav/list', 'write');

		return self::render('nav_list', $params);
	}

	public static function action_add() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_nav_list');

		$params = [];

		$page = intval(@$_GET['page']);
		$_GET['item'] = isset($_GET['item'])?abs((int)$_GET['item']):0;
		nav::$curr_pg = (empty($page)? 1: $page);

		$params['canWrite'] = CMS::hasAccessTo('nav/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? '?controller=nav&action=list': $_GET['return']);

		$params['langs'] = CMS::getLangsRegistered();
		$params['item'] = nav::getNavItem($_GET['item']);
		$params['navTypes'] = nav::$types;
		$params['navAdd'] = true;
		$params['articles'] = articles::getArticlesList(false);

		if (isset($_POST['add']) && $params['canWrite']) {
			$op_stat = nav::addNavItem();
			if ($op_stat['success']) {
				$params['refresh'] = view::create_url('nav', 'list', ['item'=> $op_stat['data']['item_id'] ]);
				$params['notice']['message'] = $op_stat['message'];
			} else {
				$params['notice']['errors'] = $op_stat['errors'];
			}
		}

		$params['menu'] = nav::getMenu();
		$params['positions'] = nav::getPositions();
		$params['count'] = nav::$items_amount;
		$params['total'] = nav::$pages_amount;
		$params['current'] = nav::$curr_pg;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = urlencode(SITE.CMS_DIR.utils::trueLink(['controller', 'action', 'q', 'page']));

		$params['navTree'] = nav::showNavTree($params['menu'], $params['current'], view::create_url('nav', 'list'));

		$params['canWrite'] = CMS::hasAccessTo('nav/list', 'write');

		return self::render('nav_list', $params);
	}

	public static function action_delete() { // 2016-10-18
		//self::$layout = 'common_layout';
		//view::$title = CMS::t('delete');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('nav/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? '?controller=nav&action=list': $_GET['return']);

		$_GET['item'] = abs((int)$_GET['item']);
		$item = nav::getNavItem($_GET['item']);

		$deleted = false;
		if ($params['canWrite'] && !empty($item['id']) ) {
			$deleted = nav::deleteNavItem($item['id']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
			//return self::render('nav_list', $params);
		}
		else	return CMS::resolve('base/404');

	}


	public static function action_autocomplete(){

		if(!utils::isAjax() || !CMS::hasAccessTo('articles/list', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		$response['message'] = 'Action is not registered.';

		if (isset($_GET['q'])) {
			$opts = articles::getArticlesAutocomplete($_GET['q']);
			$response['success'] = true;
			$response['message'] = 'Performed successfully';
			if ($opts) 	$response['data'] = $opts;
		}

		return json_encode($response);
	}

	public static function action_set_parent(){

		if(!utils::isAjax() || !CMS::hasAccessTo('nav/list', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		$response['message'] = 'Action is not registered.';

		if (!empty($_POST['parent']) && !empty($_POST['items'])) {
			$nested = nav::setNavItemParent($_POST['parent']);
			if ($nested) {
				$sorted = nav::setNavItemPosition($_POST['items']);

				if ($sorted) {
					$response['success'] = true;
					$response['message'] = 'Performed successfully';
				}
			}
		}

		return json_encode($response);
	}

	public static function action_set_position(){

		if(!utils::isAjax() || !CMS::hasAccessTo('nav/list', 'write')){
			header('HTTP/1.1 404 Not Found');
			return self::render('404');
		}

		$response['message'] = 'Action is not registered.';

		if (!empty($_POST['items'])) {
			$sorted = nav::setNavItemPosition($_POST['items']);

			if ($sorted) {
				$response['success'] = true;
				$response['message'] = 'Performed successfully';
			}
		}

		return json_encode($response);
	}

}

?>

