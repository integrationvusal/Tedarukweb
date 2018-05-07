<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class keywords {
	public static $curr_pg = 1;
	public static $pp = 10;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'keywords';


	public static function getKeywordsList() {
		$list = [];
		$where = [];

		if (!empty($_GET['q'])) {
			$where[] = "keyword LIKE '%".utils::makeSearchable($_GET['q'])."%'";
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

	public static function getKeywordsForDocument(){
		return CMS::$db->selectAll('id, keyword name', self::$tbl, [], 'ordering');
	}
	
	public static function addKeyword() { // 2016-09-13
		$response = ['success' => false, 'message' => 'insert_err', 'errors' => []];

		$data['keyword'] = utils::safeEcho( $_POST['keyword'], true);


		if(empty($data['keyword']))		$response['errors'][] = 'keywords_add_err_keyword_empty';


		if(empty($response['errors'])){
			$data['ordering'] = self::lastValueField('ordering') + 1;

			$data_id = CMS::$db->add(self::$tbl, $data);

			if ($data_id) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $data_id,
					'action' => 'add',
					'descr' => 'Keyword '.$data['keyword'].' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'insert_suc';
			}
		}
		

		return $response;
	}


	public static function addKeywordByValue($value) {

		$data['keyword'] = $value;
		$data['ordering'] = self::lastValueField('ordering') + 1;

		$data_id = CMS::$db->add(self::$tbl, $data);

		if ($data_id) {
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $data_id,
				'action' => 'add',
				'descr' => 'Keyword '.$data['keyword'].' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}

		return $data_id;
	}


	public static function deleteKeyword($id){

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

		if ($deleted) {
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'Keyword deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
	}


	private static function lastValueField($field){
		return CMS::$db->get("SELECT $field FROM `".self::$tbl."` ORDER BY id DESC LIMIT 1");
	}

}

?>