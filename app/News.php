<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;

use App\Http\Helper;

class News
{
    private static $table = 'news';

    //private static $perPage = 1;

    public static function all($type='news',$limit=false,$page=0, $dateformat = false, $category=0, $publish=false, $sef=null, $total=false){
        
        $params = [
            ':lang'=>$_SESSION['lang']
		];
 
        $type = implode(',', array_map(function($val){return "'{$val}'";}, explode(',', $type)));

        $selectedFields = 't.text, a.sef, a.is_published, a.counter, a.type, a.img,  a.add_datetime, a.publish_datetime';
        
		$translateFields = "'title', 'img', 'full'";
		
		$countTranslates = count(explode(',', $translateFields));
		
        $cat = array_fill(null,  2, '');
        if($category){
            $cat[0] = 'LEFT JOIN news_cats_rel c ON a.id = c.news_id';
            $cat[1] = 'c.category_id=:category AND';
            $params[':category'] = $category;
        }
        if($sef){
            $params[':sef'] = $sef;
            $sef = 'AND a.sef=:sef';
        }

        if($total) $selectedFields = 'COUNT(*) total';

        $publish = !$publish?"AND a.is_published = '1'":null;

    	$data = DB::select("SELECT {$selectedFields} FROM ".self::$table." a LEFT JOIN translates t ON a.id = t.ref_id {$cat[0]} AND t.ref_table ='".self::$table."' WHERE t.fieldname IN({$translateFields}) AND a.type IN({$type}) AND {$cat[1]} lang = :lang {$publish} {$sef} AND a.is_deleted='0'  ORDER BY a.publish_datetime DESC, t.id ".
        ($limit?('LIMIT '.($page*$limit*$countTranslates).', '.$limit*$countTranslates):($total?'LIMIT 1':null)) , $params);
		
        if($total) return $data;

    	$res= [];

    	for ($i=0; $i < count($data); $i+=$countTranslates){

            $_ex = explode(' ', $data[$i]['add_datetime']);

            if($dateformat)
                $_time = date($dateformat, strtotime($data[$i]['add_datetime']));
            else
                $_time = Helper::trans('custom.months', 'en', $_SESSION['lang'], date('j F Y', strtotime($data[$i]['add_datetime'])) );

            $res[] = [
                'time'=>$_time,
                'is_today'=>date('Y-m-d') === $_ex[0],
                'title'=>$data[$i]['text'],
                'sef'=>$data[$i]['sef'],
                'publish'=>$data[$i]['is_published'],
                'img'=>(!empty($data[$i+2]['text'])?$data[$i+2]['text']:$data[$i]['img']),
                'content'=>$data[$i+1]['text'],
                'counter'=>$data[$i]['counter'],
                'type'=>$data[$i]['type'],
            ];

        }  

		return $res;
    }

    public static function getContent($sef){
        return DB::select("SELECT t.text, a.img, a.counter, a.add_datetime FROM ".self::$table." a INNER JOIN translates t ON a.id = t.ref_id AND t.ref_table ='".self::$table."' WHERE (t.fieldname = 'full' OR t.fieldname='title') AND a.sef = :sef AND lang = :lang AND a.is_published = '1' AND a.is_deleted='0'  ORDER BY t.fieldname DESC", [
            ':lang' => $_SESSION['lang'],
            ':sef' => $sef
        ]);
    }
    
    public static function total($type='news', $category=0, $publish=false){
        return self::all($type, null, null, null, $category, $publish, null, true)[0]['total']/2;
    }

    public static function setCounter($sef){
    	DB::update('UPDATE '.self::$table.' SET counter=counter+1 WHERE sef = :sef', ['sef'=>$sef]);
    }

    public static function get($type='news', $dateformat=false, $category=0, $publish=false, $sef=null){
        $_data = self::all($type, 1, 0, $dateformat, $category, $publish, $sef);
        return ($_data)?$_data[0]:false;
    }
}
