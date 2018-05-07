<?php

namespace app\controllers;

use app\helpers\app;
use app\models\news;
use app\models\nav;
use app\models\gallery;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\tr;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class interview_controller extends controller {

	private static $runtime = [];

	public static function action_list() { // 2016-12-04
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_interview_list');

		$params = [];

		$page = intval(@$_GET['page']);
		news::$curr_pg = (empty($page)? 1: $page);

		if (ADMIN_TYPE!='admin') {
			$allowed_cats = nav::getEditorAllowedCats(ADMIN_ID);
		}

		news::prefiltrateRestrictedCategories(@$allowed_cats);
		$params['news'] = news::getNewsList('interview');
		news::restoreCategoriesFilter(@$allowed_cats);

		$params['count'] = news::$items_amount;
		$params['total'] = news::$pages_amount;
		$params['current'] = news::$curr_pg;

		$params['canWrite'] = CMS::hasAccessTo('interview/list', 'write');
		$params['link_sc'] = utils::trueLink(['controller', 'action', 'q', 'filter']);
		$params['link_return'] = urlencode(SITE.CMS_DIR.utils::trueLink(['controller', 'action', 'q', 'filter', 'page']));

		$params['authors'] = news::getAuthors();
		$params['allowed_cats'] = @$allowed_cats;
		$params['cats'] = nav::getCats();

		return self::render('interview_list', $params);
	}

	public static function action_add() { 
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_interview_add');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('interview/add', 'write');
		$params['link_back'] = (empty($_GET['return'])? '?controller=interview&action=list': $_GET['return']);

		if (ADMIN_TYPE!='admin') {
			$allowed_cats = nav::getEditorAllowedCats(ADMIN_ID);
		}

		if (isset($_POST['add'])) {
			$params['op'] = news::addNews('interview');
			if ($params['op']['success']) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}

		$params['langs'] = CMS::$site_langs;
		$params['allowed_cats'] = @$allowed_cats;
		$params['cats'] = nav::getCats();
		//$params['types'] = news_type::getList();
		//$params['all_tags'] = json_encode( keywords::getKeywordsForNews() );
		$params['allowed_thumb_ext'] = news::$allowed_thumb_ext;
		$params['gallery'] = gallery::getGalleriesList();

		return self::render('interview_add', $params);
	}

	public static function action_edit() { // 2016-01-15
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_interview_edit');

		$params = [];

		$params['canWrite'] = CMS::hasAccessTo('interview/edit', 'write');
		$params['link_back'] = (empty($_GET['return'])? '?controller=interview&action=list': $_GET['return']);

		$id = @(int)$_GET['id'];
		$params['news'] = news::getNews($id);
		if (empty($params['news']['id'])) {
			return CMS::resolve('base/404');
		}

		$params['langs'] = CMS::$site_langs;
		$params['cats'] = nav::getCats();
		//$params['types'] = news_type::getList();
		//$params['all_tags'] = json_encode( keywords::getKeywordsForNews() );
		//$params['selection_tags'] = json_encode( news::getKeywords($id) );
		$params['art_cats'] = news::getArtCats($id);
		//$params['doc_types'] = news::getDocTypes($id);
		$params['comments_num'] = news::countNewsComments($id);
		$params['allowed_thumb_ext'] = news::$allowed_thumb_ext;
		$params['gallery'] = gallery::getGalleriesList();


		if (isset($_POST['save']) || isset($_POST['apply']) || isset($_POST['is_published'])) {
			if (!$params['canWrite']) CMS::logout();
			$params['op'] = news::editNews($id);
			if ($params['op']['success'] && isset($_POST['save'])) {
				utils::delayedRedirect($params['link_back'], 0);
			}
		}


		return self::render('interview_edit', $params);
	}

	public static function action_delete() {
		self::$layout = 'common_layout';
		view::$title = CMS::t('delete');

		$is_restore = false;
		if(isset($_POST['restore']))	$is_restore = true;
		$_POST['querydata'] = $is_restore?$_POST['restore']:$_POST['delete'];

		$params = [];
		$params['canWrite'] = CMS::hasAccessTo('interview/delete', 'write');
		$params['link_back'] = (empty($_GET['return'])? '?controller=interview&action=list': $_GET['return']);

		$response = false;
		if ($params['canWrite']) {
			$_POST['querydata'] = self::checkRequestArray($_POST['querydata'], true);
			if(count($_POST['querydata']) > 0){
				$response = $is_restore?news::restoreNews($_POST['querydata']):news::deleteNews($_POST['querydata']);
			}
		}
		$params['op']['success'] = $response;
		$params['op']['message'] = 'del_'.($response? 'suc': 'err');

		return self::render('cms_user_delete', $params);
	}

	public static function action_ajax_set_status() { // 2016-12-04
		header('Content-type: application/json; charset=utf-8');

		$response = ['success' => false, 'message' => 'ajax_invalid_request'];

		if (!CMS::hasAccessTo('interview/ajax_set_status', 'write')) {
			$response['code'] = '403';
			$response['message'] = 'ajax_request_not_allowed_to_write';
		} else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
			$id = @(int)$_POST['id'];
			$status = @(string)$_POST['turn'];
			$updated = news::setNewsStatus($id, $status);
			if ($updated) {
				$response['success'] = true;
				$response['message'] = 'update_suc';
				$response['data']['action'] = $status;
			}
		}

		return json_encode($response);
	}

	public static function action_ajax_sort() { // 2016-12-05
		header('Content-type: application/json; charset=utf-8');

		$response = ['success' => false, 'message' => 'ajax_invalid_request'];

		if (!CMS::hasAccessTo('interview/ajax_sort', 'write')) {
			$response['code'] = '403';
			$response['message'] = 'ajax_request_not_alllowed_to_write';
		} else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
			if (!empty($_POST['start_id']) && !empty($_POST['end_id'])) {
				$sorted = news::sortNews($_POST['start_id'], $_POST['end_id']);

				if ($sorted) {
					$response['success'] = true;
					$response['message'] = 'ajax_request_performed_successfully';
				}
			}
		}

		return json_encode($response);
	}

	public static function action_ajax_paged_sort() { // 2016-12-05
		header('Content-type: application/json; charset=utf-8');

		$response = ['success' => false, 'message' => 'ajax_invalid_request'];

		if (!CMS::hasAccessTo('interview/ajax_paged_sort', 'write')) {
			$response['code'] = '403';
			$response['message'] = 'ajax_request_not_alllowed_to_write';
		} else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
			if (!empty($_POST['item_id']) && !empty($_POST['target_page'])) {
				$sorted = news::sortNewsPaged($_POST['item_id'], $_POST['target_page']);

				if ($sorted) {
					$response['success'] = true;
					$response['message'] = 'ajax_request_performed_successfully';
				}
			}
		}

		return json_encode($response);
	}

	public static function action_ajax_get_autocomplete() { // 2016-12-05
		header('Content-type: application/json; charset=utf-8');

		$response = ['success' => false, 'message' => 'ajax_invalid_request'];

		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
			if (isset($_GET['q'])) {
				$opts = news::getNewsAutocomplete($_GET['q']);

				$response['success'] = true;
				$response['message'] = 'ajax_request_performed_successfully';
				if ($opts) {
					$response['data'] = $opts;
				}
			} else {
				$response['message'] = 'ajax_request_search_query_is_not_specified';
			}
		}

		return json_encode($response);
	}

	public static function action_ajax_delete_image() { // 2016-12-05
		header('Content-type: application/json; charset=utf-8');

		$response = ['success' => false, 'message' => 'ajax_invalid_request'];

		if (!CMS::hasAccessTo('interview/ajax_delete_image', 'write')) {
			$response['code'] = '403';
			$response['message'] = 'ajax_request_not_alllowed_to_write';
		} else if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])=='xmlhttprequest') {
			if (!empty($_POST['news_id'])) {
				$deleted = news::deleteNewsImages($_POST['news_id']);

				if ($deleted) {
					$response['success'] = true;
					$response['message'] = 'Performed successfully';
				}
			}
		}

		return json_encode($response);
	}
}

?>