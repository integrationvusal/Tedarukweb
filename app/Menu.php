<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Menu
{
    private static $table = 'menu';

    public static function getMenu($parent=false, $publish=true){
    	$menu = [];
        $publish = $publish?"is_published='1' AND ":null; 
		$menu = DB::select("SELECT n.*, tr.text AS name
			FROM ".self::$table." n
				LEFT JOIN translates tr ON tr.ref_table=:menu AND tr.ref_id=n.id AND tr.lang=:default_site_lang AND tr.fieldname='name'
			WHERE $publish  is_deleted='0'".(empty($parent)? " AND parent_id='0'": (" AND parent_id='".(int)$parent."'"))."
			ORDER BY ordering ASC", [
			':default_site_lang' =>$_SESSION['lang'],
			':menu' => self::$table
		]);

		if (!empty($menu) && is_array($menu)) foreach ($menu as $i=>$item) {
			$children = self::getMenu($item['id']);
			if (!empty($children) && is_array($children)) {
				$menu[$i]['children'] = $children;
			}
		}

		return $menu;
    }

    public static function getContent($slug){

    	return current(DB::select("SELECT n.*, tr.text FROM ".self::$table." n
			LEFT JOIN translates tr ON tr.ref_table=:menu AND tr.ref_id=n.id AND tr.lang=:default_site_lang AND tr.fieldname='name'
			WHERE is_deleted='0' AND sef = :slug LIMIT 1", [
			':default_site_lang' => $_SESSION['lang'],
			':menu' => self::$table,
			':slug' => $slug
		]));
    }
}
