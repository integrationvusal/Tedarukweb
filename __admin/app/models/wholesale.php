<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class wholesale{
	public static $curr_pg = 1;
	public static $pp = 10;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'wholesale';


	public static function getList() {
		$list = [];
		$where = [];

		$c = CMS::$db->select('COUNT(id)', self::$tbl, $where);
		self::$items_amount = $c;

		$pages_amount = ceil($c/self::$pp);

		if ($pages_amount>0) {
			self::$pages_amount = $pages_amount;
			self::$curr_pg = ((self::$curr_pg>self::$pages_amount)? self::$pages_amount: self::$curr_pg);
			$start_from = (self::$curr_pg-1)*self::$pp;

			$list = CMS::$db->selectAll('a.*, b.name product', self::$tbl, $where, 'ordering', $start_from, self::$pp, ['table'=>'product', 'foreign'=>'product_id']);
		}

		return $list;
	}

    public static function add() {
		$response = ['success' => false, 'message' => 'insert_err', 'errors' => []];

        $filtered_array = [];
		self::filter_array($_POST['pricetable'], $filtered_array);
		

		$data['date'] = utils::safeEcho(date('Y-m-d', strtotime($_POST['date'])), true);
		$data['measure_id'] = utils::safeEcho(abs((int)$_POST['measure']), true);
		$data['product_id'] = utils::safeEcho(abs((int)$_POST['product']), true);
		$data['prices'] = json_encode( $filtered_array, JSON_UNESCAPED_UNICODE );
		
		if(isset($_FILES['img']) && $_FILES['img']['error'] == 0 && $_FILES['img']['size']  > 0){
			$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
			$data['img'] = utils::upload(time().'.'.$ext, $_FILES['img']['tmp_name'], '../upload/wholesale/', ['jpg', 'jpeg', 'png']);
		}

		if(empty($response['errors'])){
			$data['ordering'] = self::lastValueField('ordering') + 1;

			$data_id = CMS::$db->add(self::$tbl, $data);

			if ($data_id) {
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $data_id,
					'action' => 'add',
					'descr' => 'wholesale '.$data_id.' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'insert_suc';
			}
		}


		return $response;
	}




	public static function edit($id) {
		$response = ['success' => false, 'message' => 'update_err', 'errors' => []];

	    $filtered_array = [];
		self::filter_array($_POST['pricetable'], $filtered_array);
		
		$data['date'] = utils::safeEcho(date('Y-m-d', strtotime($_POST['date'])), true);
		$data['measure_id'] = utils::safeEcho(abs((int)$_POST['measure']), true);
		$data['product_id'] = utils::safeEcho(abs((int)$_POST['product']), true);
		$data['prices'] = json_encode( $filtered_array, JSON_UNESCAPED_UNICODE );
		
		
		if(isset($_FILES['img']) && $_FILES['img']['error'] == 0 && $_FILES['img']['size']  > 0){
		    
		    $_df = UPLOADS_DIR.'wholesale/'.$data['img'];
		    if(file_exists($_df)) unlink($df);
		        
			$ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
			$data['img'] = utils::upload(time().'.'.$ext, $_FILES['img']['tmp_name'], '../upload/wholesale/', ['jpg', 'jpeg', 'png']);
			
		}


		if(empty($response['errors'])){

			$updated = CMS::$db->mod(self::$tbl.'#'.$id, $data);

			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'edit',
				'descr' => 'wholesale'.$id.' edited by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);

			$response['success'] = true;
			$response['message'] = 'update_suc';
		}

		return $response;
	}


    public static function delete($id){

		$img = CMS::$db->getRow('SELECT img FROM `'.self::$tbl.'` WHERE `id`=:id LIMIT 1', [':id' => $id]);

		if(isset($img['img'])){
			unlink(UPLOADS_DIR.'wholesale/'.$img['img']);
		}

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

		if ($deleted) {

			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'wholesale deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
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
					'descr' => 'wholesale sorted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);
			}

		}
	}

	public static function get($id){
		return CMS::$db->getRow('SELECT * FROM `'.self::$tbl.'` WHERE `id`=:id', [':id'=>$id]);
	}


	private static function lastValueField($field){
		return CMS::$db->get("SELECT $field FROM `".self::$tbl."` ORDER BY id DESC LIMIT 1");
	}
	
	private static function filter_array($arr, &$data){
        foreach($arr as $k=>$v){
            if(is_array($v)) self::filter_array($v, $data[$k]);
            elseif($v) $data[$k] = $v;
        }
    }

}

?>