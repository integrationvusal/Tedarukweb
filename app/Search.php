<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

class Search
{
    private static $table = 'translates';

    public static function all($query){
      $arr=[];
      $data = DB::select("SELECT t.ref_table, t.text, IF(n.sef<>'', n.sef, a.sef) sef FROM ".self::$table." t LEFT JOIN news n ON n.id = t.ref_id LEFT JOIN articles a ON a.id = t.ref_id WHERE t.ref_table IN('news', 'articles') AND text LIKE :query", ['query'=>'%'.$query.'%']);
      foreach($data as $d){

        $d['text'] = trim(trim(strip_tags($d['text'])), '.');

         foreach(explode('.', $d['text']) as $p){
              $r ='';
              if(strpos($p, mb_convert_case($query, MB_CASE_TITLE)) == !false){
                $query =  mb_convert_case($query, MB_CASE_TITLE);
                $r = $p;
              }elseif(strpos($p, mb_convert_case($query, MB_CASE_LOWER)) == !false){
                $query =  mb_convert_case($query, MB_CASE_LOWER);
                $r=$p;
              }

             if($r){
				$arr[]= [
				 'is_news'=>$d['ref_table']=='news',
				 'sef'=>$d['sef'],
				 'str'=>str_replace($query, '<span class="highl">'.$query.'</span>', $p),
				];
             }
         }
      }
      return $arr;
    }

}
