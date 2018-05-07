<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

use App\Menu;
use App\Http\Helper;

class MenuWidget extends AbstractWidget
{

    protected $config = ['class'=>'nav navbar-nav', 'submenu'=>true, 'brand'=>true];


    private $level=0;

    private $icons = ['home', 'book', 'list', 'newspaper-o', 'camera', 'envelope'];

    public function run()
    {
        return $this->tree();
    }

    private function tree($datas = [], $i=0, $level=0){

        if(empty($datas)) $datas = Menu::getMenu();

    	$html = $i?'<ul class="dropdown-menu">':'<ul class="'.$this->config['class'].'">'.($this->config['brand']?'<a class="navbar-brand" href="/"><img src="/img/aqro2.png" alt="" class="logo"></a>':null);
        //$_avg = floor(count($datas)/2);

        $self_href = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),'/');
	if(strlen($self_href) == 2) $self_href='';

    	foreach ($datas as $k=>$data) {
            $icon = !$data['parent_id']?'<i class="fa fa-'.$this->icons[$level].'"></i><br/>':null;
            
            
            if($data['type'] == 'url') $_url = $data['url'];
            else{
                $countParams = count(\Route::getRoutes()->getByName($data['type'])->parameterNames());
                
    		    $_url = urldecode($data['sef'] == 'home'?'/':( $countParams?route($data['type'],['sef'=>$data['sef']]):route($data['type']) ));
            }

            $_active = (trim(parse_url($_url, PHP_URL_PATH), '/') == $self_href)?'active':null;


    		if(isset($data['children']) && $this->config['submenu']){
    			$html.='<li class="dropdown">
                            <a href="'.$_url.'" class="dropdown-toggle '.$_active.'" data-toggle="dropdown" data-hover="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$icon.$data['name'].'<span class="caret"></span></a>
                            '.$this->tree($data['children'], ++$i, $level++).'
                        </li>';
    		}
    		else{
    			
				if($_SESSION['lang'] != 'az' && $data['sef'] == 'media-bizden-yazirlar')
					$html .= '';
				else
					$html.='<li class="'.$_active.'"><a href="'.$_url.'">'.$icon.$data['name'].'</a></li>';
				
                $level++;   
                             
            }


            
    	}
    	return $html.'</ul>';
    }

}
