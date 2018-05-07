<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Settings
{
    private static $table = 'site_settings';

    public static function all(){
    	$data = [];

		$settings = DB::select("SELECT `option`, `value` FROM ".self::$table." WHERE `option` NOT IN ('cms_name', 'cms_name_formatted')");
		foreach ($settings as $set) $data[$set['option']] = html_entity_decode($set['value'], ENT_QUOTES, 'UTF-8');

		return $data;
    }
}
