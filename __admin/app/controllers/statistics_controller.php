<?php

namespace app\controllers;

use app\helpers\app;
use app\models\articles;
use app\models\comments;
use app\models\cms_users;
use app\models\site_users;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class statistics_controller extends controller {
	private static $runtime = [];

	public static function action_dashboard() { // 2016-08-21
		self::$layout = 'common_layout';
		view::$title = CMS::t('menu_item_statistics_dashboard');

		//view::appendCss(SITE_DIR.CMS_DIR.JS_DIR.'jvectormap/jquery-jvectormap-1.2.2.css');

		//view::appendJs(SITE_DIR.CMS_DIR.JS_DIR.'jvectormap/jquery-jvectormap-1.2.2.min.js');
		//view::appendJs(SITE_DIR.CMS_DIR.JS_DIR.'jvectormap/jquery-jvectormap-world-mill-en.js');
		//view::appendJs(SITE_DIR.CMS_DIR.JS_DIR.'jquery.sparkline.min.js');
		//view::appendJs(SITE_DIR.CMS_DIR.JS_DIR.'chart.min.js');

		$params = [];

		//$params['latest_registered_users'] = site_users::getLastRegistered();
		//$params['new_members'] = site_users::getCountNewComers();
		//$params['total_members'] = site_users::countUsers();
		//$params['total_comments'] = comments::countComments();
		$params['total_articles'] = articles::countArticles();
		$params['total_cms_users'] = cms_users::countUsers();

		return self::render('dashboard', $params);
	}
}

?>