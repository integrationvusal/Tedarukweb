<?php

namespace app\models;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\tr;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class banners{
	public static $curr_pg = 1;
	public static $pp = 10;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tbl = 'banners';
	public static $tr_fields = ['img'];


	public static function getList() {
		$list = [];
		
		$where[] = "b.ref_table='".self::$tbl."'"; 
		$where[] = "b.lang='".CMS::$default_site_lang."'"; 
		$where[] = "b.fieldname='img'"; 

		if (!empty($_GET['q'])) {
			$where[] = "link LIKE '%".utils::makeSearchable($_GET['q'])."%'";
		}

		$c = CMS::$db->select('COUNT(a.id)', self::$tbl, $where, null, null, null, ['table'=>'translates', 'foreign'=>'ref_id']);
		self::$items_amount = $c;
		
		$pages_amount = ceil($c/self::$pp);

		if ($pages_amount>0) {
			self::$pages_amount = $pages_amount;
			self::$curr_pg = ((self::$curr_pg>self::$pages_amount)? self::$pages_amount: self::$curr_pg);
			$start_from = (self::$curr_pg-1)*self::$pp;

			$list = CMS::$db->selectAll('a.*, b.text img', self::$tbl, $where, 'ordering', $start_from, self::$pp, ['table'=>'translates', 'foreign'=>'ref_id']);
		}

		return $list;
	}
	
	public static function add() {
		$response = ['success' => false, 'message' => 'insert_err', 'errors' => []];

		$data['link'] = utils::safeEcho( $_POST['link'], true);
		$data['title'] = utils::safeEcho( $_POST['title'], true);
		
		$translates = [];

        foreach($_FILES['img']['name'] as $k=>$file){
            if($_FILES['img']['error'][$k] == 0 && $_FILES['img']['size'][$k] > 0){
    			$translates[$k]['img'] = utils::upload(time().'.'.pathinfo($file, PATHINFO_EXTENSION), $_FILES['img']['tmp_name'][$k], '../upload/banners/', ['jpg', 'jpeg', 'png']);
            }
        }

		if(empty($response['errors'])){
			$data['ordering'] = self::lastValueField('ordering') + 1;

			$data_id = CMS::$db->add(self::$tbl, $data);

			if ($data_id) {
			    
			    foreach ($translates as $lang=>$tr_data) {
					foreach ($tr_data as $fieldname=>$value) {
						tr::store([
							'ref_table' => self::$tbl,
							'ref_id' => $data_id,
							'lang' => $lang,
							'fieldname' => $fieldname,
							'text' => $value,
						]);
					}
				}
				
				CMS::log([
					'subj_table' => self::$tbl,
					'subj_id' => $data_id,
					'action' => 'add',
					'descr' => 'Banner '.$data_id.' added by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);

				$response['success'] = true;
				$response['message'] = 'insert_suc';
			}
		}
		

		return $response;
	}

	
	public static function edit($id) {
		$response = ['success' => false, 'message' => 'update_err', 'errors' => []];

		$data['link'] = utils::safeEcho($_POST['link'], true);
		$data['title'] = utils::safeEcho($_POST['title'], true);
	
		$translates = [];

        foreach($_FILES['img']['name'] as $k=>$file){
            if($_FILES['img']['error'][$k] == 0 && $_FILES['img']['size'][$k] > 0){
    			$translates[$k]['img'] = utils::upload(time().'.'.pathinfo($file, PATHINFO_EXTENSION), $_FILES['img']['tmp_name'][$k], '../upload/banners/', ['jpg', 'jpeg', 'png']);
            }else{
                $translates[$k]['img'] = $_POST['img'][$k];
            }
        }

		if(empty($response['errors'])){

			$updated = CMS::$db->mod(self::$tbl.'#'.$id, $data);

			$response['success'] = true;
			$response['message'] = 'update_suc';
			
			foreach ($translates as $lang=>$tr_data) {
    			foreach ($tr_data as $fieldname=>$value) {
    				tr::store([
    					'ref_table' => self::$tbl,
    					'ref_id' => $id,
    					'lang' => $lang,
    					'fieldname' => $fieldname,
    					'text' => $value,
    				]);
    			}
    		}
			
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'edit',
				'descr' => 'Banner'.$id.' edited by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}

		return $response;
	}


	public static function delete($id){
		
		foreach(tr::get(self::$tbl, $id) as $lang=>$tr){
		    if(isset($tr['img'])){
		        $img = '../upload/banners/'.$tr['img'];
		        if(file_exists($img)) unlink($img);
		    }
		}

		$deleted = CMS::$db->exec('DELETE FROM `'.self::$tbl.'` WHERE `id`=:id', [':id' => $id]);

        tr::del(self::$tbl, $id); 

		if ($deleted) {
			
			CMS::log([
				'subj_table' => self::$tbl,
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'Banner deleted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
			]);
		}
	}

	public static function get($id){
	    $data = CMS::$db->getRow("SELECT a.*, b.text img FROM ".self::$tbl." a LEFT JOIN translates b ON b.ref_id = a.id WHERE b.ref_table=:ref_table AND b.fieldname=:field AND b.lang=:lang AND a.id=:id", [':id'=>$id,':ref_table'=>self::$tbl,':field'=>'img', ':lang'=>CMS::$default_site_lang]);
	    $data['translates'] = tr::get(self::$tbl, $id);
		return $data;
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
					'descr' => 'Banner sorted by '.$_SESSION[CMS::$sess_hash]['ses_adm_type'].' '.ADMIN_INFO,
				]);
			}
		
		}
	}
	
	private static function lastValueField($field){
		return CMS::$db->get("SELECT $field FROM `".self::$tbl."` ORDER BY id DESC LIMIT 1");
	}

}

?>