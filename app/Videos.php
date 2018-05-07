<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Videos
{
    private static $table = 'videos';

    public static function get($fields ='link'){
    	if(is_array($fields)) $f = implode(',', $fields);
    	$data = DB::select("SELECT {$f} FROM ".self::$table." ORDER BY ordering DESC LIMIT 1")[0];
    	if(!is_array($fields)) return $data[$fields];
    	return $data;
    }
    
    public static function all(){
		return  DB::select("SELECT * FROM ".self::$table." ORDER BY ordering");
    }

}
