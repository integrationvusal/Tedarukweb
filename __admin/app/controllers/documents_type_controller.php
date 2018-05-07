<?php

namespace app\controllers;

use app\helpers\app;
use app\models\documents_type;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class documents_type_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-09-12
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_documents_type_list');

		$params = [];

		$page = intval(@$_GET['page']);
		documents_type::$curr_pg = (empty($page)? 1: $page);

		$params['alldata'] = documents_type::getList();
		$params['count'] = documents_type::$items_amount;
		$params['total'] = documents_type::$pages_amount;
		$params['current'] = documents_type::$curr_pg;

		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q']);
		$params['link_return'] = view::create_url('documents_type', 'list');

		$params['canWrite'] = CMS::hasAccessTo('documents_type/list', 'read');

		return self::render('documents_type_list', $params);
	}

	public static function action_add() { // 2016-09-13
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_documents_type_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('documents_type/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('documents_type', 'list'): $_GET['return']);

		if (isset($_POST['add'])) {
			$params['op'] = documents_type::addDocumentType();
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		return self::render('documents_type_add', $params);
	}

	public static function action_delete() { // 2016-10-18

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('documents_type/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? view::create_url('documents_type', 'list'): $_GET['return']);

		$_POST['delete'] = abs((int)$_POST['delete']);

		$deleted = false;
		if ($params['canWrite'] && !empty($_POST['delete']) ) {
			$deleted = documents_type::deleteDocumentType($_POST['delete']);
			$params['op']['success'] = $deleted;
			$params['op']['message'] = 'del_'.($deleted? 'suc': 'err');
			utils::delayedRedirect($params['link_back'], 0);
		}
		else	return CMS::resolve('base/404');
	}

}

?>