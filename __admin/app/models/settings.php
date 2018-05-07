<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class settings {
	public static $curr_pg = 1;
	public static $pp = 11;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'site_settings';


	public static function getList() {
		$list = [];
		$where = ["`option` NOT IN ('cms_name', 'cms_name_formatted')"];


		if (!empty($_GET['q'])) {
			$where[] = "name LIKE '%".utils::makeSearchable($_GET['q'])."%'";
		}

		$where = join(' AND ',$where);

		$c = CMS::$db->select('COUNT(id)', self::$tbl, $where);
		self::$items_amount = $c;

		$pages_amount = ceil($c/self::$pp);

		if ($pages_amount>0) {
			self::$pages_amount = $pages_amount;
			self::$curr_pg = ((self::$curr_pg>self::$pages_amount)? self::$pages_amount: self::$curr_pg);
			$start_from = (self::$curr_pg-1)*self::$pp;

			$list = CMS::$db->selectAll('*', self::$tbl, $where, 'id', $start_from, self::$pp);
		}

		return $list;
	}

	public static function edit($id) { // 2016-09-13
		$response = ['success' => false, 'message' => 'update_err', 'errors' => []];

		$data['option'] = utils::safeEcho($_POST['option'], true);
		$data['value'] = utils::safeEcho($_POST['value'], true);


		if(empty($data['option']))	$response['errors'][] = 'settings_edit_err_option_empty';
		if(empty($data['value']))	$response['errors'][] = 'settings_edit_err_value_empty';


		if(empty($response['errors'])){

			$updated = CMS::$db->mod(self::$tbl.'#'.$id, $data);

				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $id,
					'action' => 'edit',
					'descr' => 'Setting '.$data['option'].' edited by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'update_suc';
		}

		return $response;
	}


	public static function delete($id){

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

		if ($deleted) {
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'Setting deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
	}

	public static function get($id){
		return CMS::$db->getRow('SELECT * FROM `'.self::$tbl.'` WHERE `id`=:id', [':id'=>$id]);
	}

}

?>