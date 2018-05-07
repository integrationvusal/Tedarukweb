<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Banners
{
    private static $table = 'banners';

    public static function all(){
        return DB::select("SELECT a.*, b.text img FROM ".self::$table." a LEFT JOIN translates b ON b.ref_id=a.id WHERE b.fieldname=:field AND b.lang = :lang ORDER BY a.ordering", ['field'=>'img', 'lang'=>$_SESSION['lang']]);
    }

}
