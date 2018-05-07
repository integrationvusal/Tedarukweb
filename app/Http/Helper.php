<?php

namespace App\Http;

class Helper
{
    public static function filename($path)
    {
        return pathinfo($path, PATHINFO_FILENAME);
    }

    public static function trans($id, $from, $to, $data){
    	return str_replace(trans($id, [], null, $from), trans($id, [], null, $to), $data);
    }

    public static function youtube($id, $width='100%', $height=418, $style='margin-bottom: -6px;'){
    	$id = explode('?v=',$id);
    	$id = $id[1]; 

    	return '<iframe width="'.$width.'" height="'.$height.'" style= "'.$style.'" src="https://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe>';
    }

    public static function youtube_img($link){
		if(strpos($link, 'v=') !== false){
			$link = explode('v=', $link);
			return 'https://img.youtube.com/vi/'.$link[1].'/mqdefault.jpg';
		}
        return '/img/no_video.png';
	}
	
	public static function video_link($link){
		if(strpos($link, 'v=') !== false) return $link;
		return '/upload/videos/'.$link;
	}

    public static function video_category($category){
        return trim($category, ';');
    }

    public static function parseurl($hasLang){
        $_url = trim($_SERVER['REQUEST_URI'],'/');
        
        $par = [];
		foreach(explode('/', $_url) as $p)
			if(strlen($p)!=2) $par[]=$p; 
            
        return implode('/', $par);
    }
	
	public static function getImgFromContent($data){
		preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $data, ${0});
		return isset(${0}['src'])?${0}['src']:'';
	}
}