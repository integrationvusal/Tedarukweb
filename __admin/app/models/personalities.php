<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class personalities{
	public static $curr_pg = 1;
	public static $pp = 10;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'personalities';


	public static function getList() {
		$list = [];
		$where = [];

		if (!empty($_GET['q'])) {
			$where[] = "(surname LIKE '%".utils::makeSearchable($_GET['q'])."%') OR (name LIKE '%".utils::makeSearchable($_GET['q'])."%') OR (patronymic LIKE '%".utils::makeSearchable($_GET['q'])."%')";
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

	public static function add() {
		$response = ['success' => false, 'message' => 'insert_err', 'errors' => []];

		$data['title'] = utils::safeEcho( $_POST['title'], true);
		$data['fio'] = utils::safeEcho( $_POST['fio'], true);
		$data['birth'] = utils::safeEcho( $_POST['birth'], true);
		$data['deadth'] = utils::safeEcho( $_POST['deadth'], true);
		$data['content'] = trim( $_POST['content']);

		if(isset($_FILES['img']) && $_FILES['img']['error']  == 0){
			$_ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
			if(in_array($_ext, ['jpg', 'jpeg', 'png'])){
				$data['img'] = time().'.'.$_ext;
				move_uploaded_file($_FILES['img']['tmp_name'],  UPLOADS_DIR.'photos/'.$data['img']);
			}
		}

		if(empty($response['errors'])){
			$data['ordering'] = self::lastValueField('ordering') + 1;

			$data_id = CMS::$db->add(self::$tbl, $data);

			if ($data_id) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $data_id,
					'action' => 'add',
					'descr' => 'Personalities '.$data_id.' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'insert_suc';
			}
		}


		return $response;
	}


	public static function edit($id) {
		$response = ['success' => false, 'message' => 'update_err', 'errors' => []];

		$data['title'] = utils::safeEcho( $_POST['title'], true);
		$data['fio'] = utils::safeEcho( $_POST['fio'], true);
		$data['birth'] = utils::safeEcho( $_POST['birth'], true);
		$data['deadth'] = utils::safeEcho( $_POST['deadth'], true);
		$data['img'] = utils::safeEcho( $_POST['img'], true);
		$data['content'] = trim( $_POST['content']);

		if(isset($_FILES['img']) && $_FILES['img']['error']  == 0){
			$_ext = strtolower(pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION));
			if(in_array($_ext, ['jpg', 'jpeg', 'png'])){
				if(!empty($data['img'])) 	unlink(UPLOADS_DIR.'photos/'.$data['img']);
				$data['img'] = time().'.'.$_ext;
				move_uploaded_file($_FILES['img']['tmp_name'],  UPLOADS_DIR.'photos/'.$data['img']);
			}
		}

		if(empty($response['errors'])){

			$updated = CMS::$db->mod(self::$tbl.'#'.$id, $data);

			if ($updated) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $id,
					'action' => 'edit',
					'descr' => 'Personalities'.$id.' edited by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'update_suc';
			}
		}

		return $response;
	}


	public static function delete($id){

		$img = CMS::$db->getRow('SELECT img FROM `'.self::$tbl.'` WHERE `id`=:id LIMIT 1', [':id' => $id]);

		if(isset($img['img'])){
			unlink(UPLOADS_DIR.'photos/'.$img['img']);
		}

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

		if ($deleted) {

			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'personalities deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
	}

	public static function get($id){
		return CMS::$db->getRow('SELECT * FROM `'.self::$tbl.'` WHERE `id`=:id', [':id'=>$id]);
	}


	public static function ajax_sort($items, $cal_start){

		$cal_start = abs((int)$cal_start);

		foreach($items as $k=>$item){
			$item = abs((int)$item);

			$sort = CMS::$db->exec('UPDATE `'.self::$tbl.'` SET ordering = :ordering WHERE id = :id', [':ordering'=>($cal_start + $k + 1), ':id' => $item]);

			if($sort){
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $item,
					'action' => 'ajax_sort',
					'descr' => 'personalities sorted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);
			}

		}
	}

	private static function lastValueField($field){
		return CMS::$db->get("SELECT $field FROM `".self::$tbl."` ORDER BY id DESC LIMIT 1");
	}

}

?>