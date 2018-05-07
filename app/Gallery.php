<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Gallery
{
    private static $table = 'gallery';

    public static function all(){
    	$res = [];

    	$res = self::scan_dir(base_path('upload/albums'), $res);

    	return $res;
    }

    private static function scan_dir($dir, &$arr) {
		$files = array_values(array_filter(scandir($dir), function($val){ return !in_array($val, ['.', '..']); }));
		foreach ($files as $file) {
			if(is_dir($dir.'/'.$file)) { self::scan_dir($dir.'/'.$file, $arr); }
			if(is_file($dir.'/'.$file)) {  $arr[basename($dir)][] = $file; }   
		}
		return $arr;
	}

}
