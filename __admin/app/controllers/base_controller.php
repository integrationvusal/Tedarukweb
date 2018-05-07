<?php

namespace app\controllers;

use app\helpers\app;
use app\models\cms_users;
use jewish\backend\CMS;
use jewish\backend\base\controller;
use jewish\backend\helpers\security;
use jewish\backend\helpers\tr;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class base_controller extends controller {
	private static $runtime = [];

	public static function action_404() { // 2016-08-18
		header('HTTP/1.1 404 Not Found');

		self::$layout = 'simple_layout';
		view::$title = CMS::t('404_title');

		return self::render('404');
	}

	public static function action_403() { // 2016-09-05
		header('HTTP/1.1 403 Forbidden');

		self::$layout = 'simple_layout';
		view::$title = CMS::t('403_title');

		return self::render('403');
	}

	public static function action_sign_in() { // 2016-08-14
		self::$layout = 'simple_layout';
		
		view::$title = CMS::t('login_title');

		$response = [
			'success' => false,
			'message' => 'undefined'
		];
		if (isset($_POST['ad_send'])) {
			$user = cms_users::getUserByLogin(@$_POST['ad_login']);
			if (empty($user['id'])) {
				$response['errors'][] = 'login_err';
			} else if (!security::validatePassword($user['password'], @$_POST['ad_password'], CMS::$salt)) {
				$response['errors'][] = 'login_err';
			} else if ($user['is_blocked']) {
				$response['errors'][] = 'login_err_blocked';
			} else {
				CMS::login($user);
				$response = [
					'success' => true,
					'message' => 'login_suc'
				];
				utils::redirect(SITE.CMS_DIR.(empty($_SERVER['QUERY_STRING'])? view::create_url(CMS::$mainController, CMS::$mainAction): ('?'.$_SERVER['QUERY_STRING'])));
			}
		}
		return self::render('sign_in', [
			'response' => $response
		]);
	}

	public static function action_ulogin() { // 2016-08-20
		$response = [
			'success' => false,
			'message' => 'undefined'
		];

		$s = file_get_contents('http://ulogin.ru/token.php?token='.$_POST['token'].'&host='.$_SERVER['HTTP_HOST']);
		$user_data = json_decode($s, true);

		if (!isset($user_data['error']) && !empty($user_data['uid'])) {
			$user = CMS::getAdminUser($user_data['email']);

			if (!empty($user['id'])) {
				CMS::login($user);
				$response = [
					'success' => true,
					'message' => 'login_suc'
				];
			}
		} else {
			$response['message'] = @(string)$user_data['error'];
		}

		return json_encode($response);
	}

	public static function action_sign_out() { // 2016-08-18
		CMS::logout();
		return '';
	}

	public static function action_password_recovery() { // 2016-08-13
		self::$layout = 'simple_layout';
		view::$title = CMS::t('password_recovery_title');

		$response = [
			'success' => false,
			'message' => 'undefined'
		];
		if (!empty($_POST['recover'])) {
			if (!utils::is_valid_email($_POST['email'])) {
				$response['errors'][] = 'password_recovery_err_email_invalid';
			} else {
				$user = CMS::getAdminUser($_POST['email']);
				if (empty($user['id'])) {
					$response['errors'][] = 'password_recovery_err_user_not_found';
				} else {
					$to = $user['login'];
					$msg = [
						'subject' => 'Profit CMS '.CMS::$version.' - '.CMS::t('password_recovery_subj'),
						'message' => CMS::t('password_recovery_msg', [
							'{username}' => $user['name'],
							'{recovery_link}' => utils::safeEcho(SITE.CMS_DIR.'?controller=base&action=change_password&login='.$user['login'].'&hash='.CMS::generateAccountHash($user, 'password_recovery'), 1)
						])
					];
					$sended = app::sendEmail($to, $msg);
					if ($sended) {
						$response = [
							'success' => true,
							'message' => 'password_recovery_suc_email_sended'
						];
					} else {
						$response['errors'][] = 'password_recovery_err_email_cannot_send';
					}
				}
			}
		}
		return self::render('password_recovery', [
			'response' => $response
		]);
	}

	public static function action_change_password() { // 2016-08-14
		self::$layout = 'simple_layout';
		view::$title = CMS::t('password_change_title');

		$response = [
			'success' => false,
			'message' => 'undefined'
		];
		if (!empty($_POST['change'])) {
			if (!utils::checkPass(@$_POST['password'])) {
				$response['errors'][] = 'cms_user_pwd_err';
			} else {
				$user = CMS::getAdminUser($_GET['login']);
				if (empty($user['id'])) {
					$response['errors'][] = 'password_recovery_err_user_not_found';
				} else if (!CMS::checkAccountHash(@$_GET['hash'], $user, 'password_recovery')) {
					$response['errors'][] = 'password_change_err_account_hash_expired';
				} else {
					$new_hash = security::generatePasswordHash($_POST['password'], CMS::$salt);
					CMS::$db->mod('cms_users#'.$user['id'], [
						'password' => $new_hash
					]);
					$response = [
						'success' => true,
						'message' => 'password_change_suc'
					];
				}
			}
		}
		return self::render('change_password', [
			'response' => $response
		]);
	}
}

?>