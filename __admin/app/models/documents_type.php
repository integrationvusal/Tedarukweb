<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class documents_type {
	public static $curr_pg = 1;
	public static $pp = 10;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'documents_type';


	public static function getList() {
		$list = [];
		$where = [];

		if (!empty($_GET['q'])) {
			$where[] = "type LIKE '%".utils::makeSearchable($_GET['q'])."%'";
		}

		$c = CMS::$db->select('COUNT(id)', self::$tbl, $where);
		self::$items_amount = $c;
		
		$pages_amount = ceil($c/self::$pp);

		if ($pages_amount>0) {
			self::$pages_amount = $pages_amount;
			self::$curr_pg = ((self::$curr_pg>self::$pages_amount)? self::$pages_amount: self::$curr_pg);
			$start_from = (self::$curr_pg-1)*self::$pp;

			$list = CMS::$db->selectAll('*', self::$tbl, $where, 'ordering', $start_from, self::$pp);
		}

		return $list;
	}
	
	public static function addDocumentType() { // 2016-09-13
		$response = ['success' => false, 'message' => 'insert_err', 'errors' => []];

		$data['type'] = utils::safeEcho($_POST['type'], true);


		if(empty($data['type']))	$response['errors'][] = 'documents_type_add_err_type_empty';


		if(empty($response['errors'])){
			$data['ordering'] = self::lastValueField('ordering') + 1;

			$data_id = CMS::$db->add(self::$tbl, $data);

			if ($data_id) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $data_id,
					'action' => 'add',
					'descr' => 'Document type '.$data['type'].' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'insert_suc';
			}
		}

		return $response;
	}


	public static function deleteDocumentType($id){

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

		if ($deleted) {
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'Document type deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
	}


	private static function lastValueField($field){
		return CMS::$db->get("SELECT $field FROM `".self::$tbl."` ORDER BY id DESC LIMIT 1");
	}

}

?>