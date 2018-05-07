<?php

namespace jewish\backend\helpers;

use jewish\backend\CMS;
use jewish\backend\helpers\security;
use jewish\backend\helpers\utils;

if (!defined('_VALID_PHP')) die('Direct access to this location is not allowed.');

class view {
	public static $title = '';
	public static $corporate_color = 'bf1e23';
	public static $pagination_defaults = []; // defined at the end of file after class definition to workaround PHP limitations for properties default values initialization statements
	public static $menu = [
		[
			'name' => 'menu_block_dashboard',
			'icon' => 'dashboard',
			//'selected' => ['statistics/dashboard'],
			'subs' => [
				'statistics/dashboard' => [
					'url' => '?controller=statistics&action=dashboard'
				]
			]
		],
		[
			'name' => 'menu_item_nav',
			'icon' => 'th',
			'selected' => ['nav/list'],
			'url' => '?controller=nav&action=list'
		],
		[
			'name' => 'menu_block_content',
			'icon' => 'files-o',
			'selected' => ['articles/list', 'articles/add', 'articles/edit', 'banners/list', 'banners/add', 'banners/edit', 'slider/list', 'slider/add', 'slider/edit', 'gallery/list', 'gallery/add', 'gallery/edit', 'photoday/list', 'photoday/edit', 'news/list', 'news/add', 'news/edit', 'interview/list', 'interview/add', 'interview/edit','clauses/list', 'clauses/add', 'clauses/edit', 'videos/list', 'videos/add', 'videos/edit', 'markets/list', 'markets/add', 'markets/edit', 'measure/list', 'measure/add', 'measure/edit', 'product/list', 'product/add', 'product/edit', 'wholesale/list', 'wholesale/add', 'wholesale/edit'],
			'subs' => [
				'articles/list' => [
					'selected' => ['articles/list', 'articles/add', 'articles/edit']
				],
				'news/list' => [
					'selected' => ['news/list', 'news/add', 'news/edit']
				],
				

				/*'interview/list' => [
					'selected' => ['interview/list', 'interview/add', 'interview/edit']
				],
				'clauses/list' => [
					'selected' => ['clauses/list', 'clauses/add', 'clauses/edit']
				],*/
				'videos/list' => [
					'selected' => ['videos/list', 'videos/add', 'videos/edit']
				],
				'gallery/list' => [
					'selected' => ['gallery/list', 'gallery/add', 'gallery/edit']
				],
				/*'photoday/list' => [
					'selected' => ['photoday/list', 'photoday/edit']
				],*/
				'banners/list' => [
					'selected' => ['banners/list', 'banners/edit','banners/add']
				],
				
				/*'slider/list' => [
					'selected' => ['slider/list', 'slider/add', 'slider/edit']
				],
				
				'wholesale/list' => [
					'selected' => ['wholesale/list', 'wholesale/add', 'wholesale/edit']
				],
				
				'product/list' => [
					'selected' => ['product/list', 'product/add', 'product/edit']
				],
				
				'markets/list' => [
					'selected' => ['markets/list', 'markets/add', 'markets/edit']
				],
				
				'measure/list' => [
					'selected' => ['measure/list', 'measure/add', 'measure/edit']
				],*/
				
			]
		],
		[
			'name' => 'menu_block_cms_users',
			'icon' => 'users',
			'selected' => ['cms_users/list', 'cms_users/add', 'cms_users/delete', 'cms_users/edit'],
			'subs' => [
				'cms_users/list' => [
					'selected' => ['cms_users/list', 'cms_users/add', 'cms_users/delete', 'cms_users/edit']
				]
			]
		],
		[
			'name' => 'menu_block_settings',
			'icon' => 'cog',
			'selected' => ['settings/list', 'settings/edit'],
			'url' => '?controller=settings&action=list'
		]
	];


	public static $newMenu = [
		[
			'icon' => 'list-ul',
			'selected' => ['documents/list', 'documents/add', 'documents/edit']
		],

		[
			'icon' => 'hashtag',
			'selected' => ['nav/list']
		],

		[
			'icon' => 'folder-open',
			'selected' => ['documents_type/list', 'documents_type/add']
		],

		[
			'icon' => 'map-marker',
			'selected' => ['keywords/list', 'keywords/add']
		],

		[
			'icon' => 'link',
			'selected' => ['actual_links/list', 'actual_links/add']
		],

		[
			'icon' => 'users',
			'selected' => ['cms_users/list', 'cms_users/add', 'cms_users/edit']
		],

		[
			'icon' => 'photo',
			'selected' => ['banners/list', 'banners/add', 'banners/edit']
		],

		[
			'icon' => 'cog',
			'selected' => ['settings/list', 'settings/edit']
		],
	];

	private static $css = ['all' => [], 'screen' => [], 'print' => []];
	private static $js = ['all' => []];
	private static $rendering_template;


	public static function appendCss($css, $media='screen') {
		if (empty($css)) {return false;}
		if (!isset(self::$css[$media])) {self::$css[$media] = [];}
		self::$css[$media][] = $css;
		return true;
	}

	public static function prependCss($css, $media='screen') {
		if (empty($css)) {return false;}
		if (!isset(self::$css[$media])) {self::$css[$media] = [];}
		return array_unshift(self::$css[$media], $css);
	}

	public static function outputCssList() {
		$html = '';
		if (is_array(self::$css) && count(self::$css)) foreach (self::$css as $media=>$css_list) {
			if (is_array($css_list) && count($css_list)) {
				$css_list = array_unique($css_list);
				foreach ($css_list as $src) {
					$html.="\t\t".'<link rel="stylesheet" href="'.$src.'" type="text/css" media="'.$media.'" />'."\n";
				}
			}
		}
		return $html;
	}

	public static function appendJs($js, $cond='all') {
		if (empty($js)) {return false;}
		if (!isset(self::$js[$cond])) {self::$js[$cond] = [];}
		self::$js[$cond][] = $js;
		return true;
	}

	public static function prependJs($js, $cond='all') {
		if (empty($js)) {return false;}
		if (!isset(self::$js[$cond])) {self::$js[$cond] = [];}
		return array_unshift(self::$js[$cond], $js);
	}

	public static function outputJsList() {
		$html = '';
		if (is_array(self::$js) && count(self::$js)) foreach (self::$js as $cond=>$js_list) {
			if (is_array($js_list) && count($js_list)) {
				$js_list = array_unique($js_list);
				foreach ($js_list as $src) {
					$html.="\t\t".(($cond=='all')? '': ('<!--[if '.$cond.']>'."\n\t\t\t")).'<script type="text/javascript" src="'.$src.'"></script>'.(($cond=='all')? '': ("\n\t\t".'<![endif]-->'))."\n";
				}
			}
		}
		return $html;
	}

	public static function pg($opts=array()) {
		$pg = array_merge(self::$pagination_defaults, $opts);
		$html = '';
		if (($pg['total']<=1) && ($pg['hide_if_only'])) {return $html;}
		$p = 1;
		$numbers = array();
		$html.=(empty($pg['container_tagname'])? '': ('<'.$pg['container_tagname'].(empty($pg['class'])? '': (' class="'.$pg['class'].'"')).(empty($pg['id'])? '': (' id="'.$pg['id'].'"')).'>'));
		$capsule = (empty($pg['inner_container_tagname'])? '%s': ('<'.$pg['inner_container_tagname'].'>%s</'.$pg['inner_container_tagname'].'>'));
		$stop = $pg['total'];
		if ($pg['scope']) {
			if ($pg['current']>1) {
				$numbers[] = '<a href="'.sprintf($pg['page_url'], '1').'" title="'.$pg['scope_start_title'].'"'.(empty($pg['scope_start_class'])? '': (' class="'.$pg['scope_start_class'].'"')).'>'.sprintf($capsule, $pg['scope_start_text']).'</a>';
				$numbers[] = '<a href="'.sprintf($pg['page_url'], ($pg['current']-1)).'" title="'.$pg['scope_prev_title'].'"'.(empty($pg['scope_prev_class'])? '': (' class="'.$pg['scope_prev_class'].'"')).'>'.sprintf($capsule, $pg['scope_prev_text']).'</a>';
			}
			$side_c = ($pg['scope_width']-($pg['scope_width']%2))/2;
			if ($pg['current']<=$side_c) { // show from begin
				$stop = (($pg['scope_width']<$pg['total'])? $pg['scope_width']: $pg['total']);
			} else if ($pg['current']>($pg['total']-$side_c)) { // show from end
				if ($pg['total']>=$pg['scope_width']) {
					$p = $pg['total']-$pg['scope_width']+1;
				} else {
					$p = (($pg['total']>$side_c)? ($pg['total']-$side_c): 1);
				}
				$stop = $pg['total'];
			} else { // scope in the middle
				$p = $pg['current']-$side_c;
				$stop = $pg['current']+($side_c-(($pg['scope_width']%2)? 0: 1));
			}
		}
		while ($p<=$stop) {
			$number = sprintf($capsule, $p);
			$link = sprintf($pg['page_url'], $p);
			if ($p!=$pg['current']) {
				$number = '<a href="'.$link.'" title=""'.(empty($pg['number_class'])? '': (' class="'.$pg['number_class'].'"')).'>'.$number.'</a>';
			} else {
				$act_open = '';
				$act_close = '';
				if (!empty($pg['active_container_tagname'])) {
					$act_open = '<'.$pg['active_container_tagname'].(empty($pg['act_class'])? '': (' class="'.$pg['act_class'].'"')).(empty($pg['act_id'])? '': (' id="'.$pg['act_id'].'"')).'>';
					$act_close = '</'.$pg['active_container_tagname'].'>';
				}
				$number = $act_open.$number.$act_close;
			}
			$numbers[] = $number;
			$p++;
		}
		if ($pg['scope']) {
			if ($pg['current']<$pg['total']) {
				$numbers[] = '<a href="'.sprintf($pg['page_url'], ($pg['current']+1)).'" title="'.$pg['scope_next_title'].'"'.(empty($pg['scope_next_class'])? '': (' class="'.$pg['scope_next_class'].'"')).'>'.sprintf($capsule, $pg['scope_next_text']).'</a>';
				$numbers[] = '<a href="'.sprintf($pg['page_url'], $pg['total']).'" title="'.$pg['scope_end_title'].'"'.(empty($pg['scope_end_class'])? '': (' class="'.$pg['scope_end_class'].'"')).'>'.sprintf($capsule, $pg['scope_end_text']).'</a>';
			}
		}
		$html.=implode($pg['delimiter'], $numbers);
		$html.=(empty($pg['container_tagname'])? '': ('</'.$pg['container_tagname'].'>'));

		return $html;
	}

	public static function create_url($controller, $action = [], $params = []){
		if(strpos($controller, '/') !== false){
			$contAct = explode('/', trim($controller, '/'));
			return '?'.http_build_query( array_merge(['controller'=>$contAct[0]], ['action'=>$contAct[1]], $action) );
		}
		return '?'.http_build_query( array_merge(['controller'=>$controller], ['action'=>$action], $params) );
	}

	public static function render($fname, $data=[]) {
		$CSRF_token = security::$CSRF_token;

		self::$rendering_template = $fname;
		if (!empty($data) && is_array($data)) {
			extract($data);
		}

		/*if (!is_file(self::$rendering_template)) {
			return CMS::resolve('base', '404');
		}*/

		ob_start();
		include self::$rendering_template;

		return ob_get_clean();
	}

	public static function widget($widget_name, $options=[]) {
		if (is_file(CONTROLLER_DIR.$widget_name.'_widget_controller.php')) {
			$controller_name = 'app\\controllers\\'.$widget_name.'_widget_controller';
			$controller_name::$options = $options;
			call_user_func($controller_name.'::init');
			return call_user_func($controller_name.'::run');
		}
		return '';
	}

	public static function htmlSelectReSortOptions($optionslist) {
		$nl_list = str_replace('</option><', "</option>\n<", $optionslist);
		$list_options = explode("\n", $nl_list);
		$list_titles = array();
		$found = @preg_match_all('/\<option(.*)\>([0-9a-zа-яА-ЯA-ZüöğıəçşёÜÖĞİƏÇŞЁ\-\(\)\s]{1,64})\<\/option\>/isDUu', $optionslist, $list_titles);
		if ($found) {
			$list_titles = $list_titles[2];
			asort($list_titles);
			$ordering = array_combine(array_keys($list_titles), array_keys($list_options));
			ksort($ordering);
			$optionslist = array_combine(array_values($ordering), array_values($list_options));
			ksort($optionslist);
			$optionslist = implode('', $optionslist);
		}
		return $optionslist;
	}

	public static function notice($errors, $type='danger', $title='auto') { // 2016-09-05
		$icons = [
			'danger' => 'fa-ban',
			'info' => 'fa-info',
			'warning' => 'fa-warning',
			'success' => 'fa-check',
		];
		$html = '<div class="alert alert-'.$type.' alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h4><i class="icon fa '.$icons[$type].'"></i> '.CMS::t(($title=='auto')? 'alert': $title).'</h4>';
		if (!empty($errors)) {
			if (!is_array($errors)) {$errors = [$errors];}
			foreach ($errors as $e) {
				$html.='<p>'.CMS::t($e).'</p>';
			}
		}
		$html.='</div>';

		return $html;
	}

	public static function callout($errors, $type='danger', $title='') { // 2016-09-05
		$html = '<div class="callout callout-'.$type.'">';
		if (!empty($title)) {
			$html.='<h4>'.CMS::t(($title=='auto')? 'alert': $title).'</h4>';
		}
		if (!empty($errors)) {
			if (!is_array($errors)) {$errors = [$errors];}
			foreach ($errors as $e) {
				$html.='<p>'.CMS::t($e).'</p>';
			}
		}
		$html.='</div>';

		return $html;
	}

	public static function genAttrString($attrs, $prefix='') {
		$res = '';
		if (!empty($attrs) && is_array($attrs)) foreach ($attrs as $k=>$v) {
			if (is_array($v)) {
				$res.=self::genAttrString($v, $prefix.$k.'-');
			} else {
				$res.=' '.utils::sanitizeStringByWhitelist($prefix.$k, 'a-z\-\_').'="'.utils::safeEcho($v, 1).'"';
			}
		}
		return $res;
	}

	public static function browse($opts=[]) { // 2016-09-13
		$defaults = [
			'type' => 'file',
			'name' => 'file',
			'placeholder' => CMS::t('browse_placeholder'),
			'button_text' => CMS::t('browse_button_text')
		];
		$opts = array_merge($defaults, $opts);
		$attrs = utils::array_exclude_keys($opts, ['button_text']);
		$attrs_string = self::genAttrString($attrs);

		ob_start();

		?><div class="customizedFileInputBox">
			<label class="customizedFileInputOverlay input-group">
				<input<?=$attrs_string;?> />
				<div class="customizedFileInput form-control"><?=$opts['placeholder'];?></div>
				<div class="input-group-btn">
					<div class="btn btn-success"><i class="fa fa-paperclip" aria-hidden="true"></i> <?=$opts['button_text'];?></div>
				</div>
			</label>
		</div><?php

		return ob_get_clean();
	}

	public static function gravatar($email, $size=80, $attrs=[]) {
		$hash = md5(strtolower(trim($email)));
		$url = 'https://www.gravatar.com/avatar/'.$hash;
		$params = [];
		if ($size!=80) {$params['s'] = (int)$size;}
		//if (@CMS_ENV!='dev') {$params['d'] = SITE.CMS_DIR.'templates/default/images/noimg.jpg';}
		$params['d'] = 'mm';
		if (!empty($params)) {$url.='?'.http_build_query($params);}
		if (!isset($attrs['alt'])) {$attrs['alt'] = '';}
		$img = '<img src="'.utils::safeEcho($url, 1).'"'.self::genAttrString($attrs).' />';
		return $img;
	}

	public static function menu() {
		$html = '';
		$cur_page = @$_GET['controller'].'/'.@$_GET['action'];

		foreach (self::$menu as $i=>$block) {
			if (empty($block['selected'])) {$block['selected'] = array_keys(@$block['subs']);}
			if (empty($block['icon'])) {$block['icon'] = 'circle-o';}
			$html.='<li class="treeview'.(in_array($cur_page, $block['selected'])? ' active': '').'">
				<a href="'.(!empty($block['url'])?$block['url']:'#').'">
					<i class="fa fa-'.$block['icon'].'"></i>
					<span>'.CMS::t($block['name']).'</span>
					'.(!empty($block['subs'])?'
					<span class="pull-right-container">
						<i class="fa fa-angle-left pull-right"></i>
					</span>
					':null).'
				</a>

				';
			if (!empty($block['subs'])) {
				$html.='<ul class="treeview-menu">';
				foreach ($block['subs'] as $page=>$item) {
					if (empty($item['name'])) {$item['name'] = 'menu_item_'.str_replace('/', '_', $page);}
					if (empty($item['selected'])) {$item['selected'] = [$page];}
					if (empty($item['icon'])) {$item['icon'] = 'circle-o';}
					if (empty($item['url'])) {
						list($controller, $action) = explode('/', $page);
						$item['url'] = '?controller='.$controller.'&action='.$action;
					}
					$counter = '';
					if (!empty($item['callback'])) {
						list($counter_model, $counter_method) = explode('.', $item['callback']);
						$counter = call_user_func('app\\models\\'.$counter_model.'::'.$counter_method);
					}
					$html.='<li'.(in_array($cur_page, $item['selected'])? ' class="active"': '').'>
						<a href="'.utils::safeEcho($item['url'], 1).'">
							<!-- <i class="fa fa-'.$item['icon'].'"></i> -->
							'.CMS::t($item['name']).(empty($counter)? '': ('
							<span class="pull-right-container">
								<span class="label pull-right bg-red">'.$counter.'</span>
							</span>')).'
						</a>
					</li>';
				}
				$html.='</ul>';
			}
			$html.='</li>';
		}

		return $html;
	}

	public static function newmenu(){

		$html = '';

		foreach(self::$newMenu as $menu){
			$class = in_array(CMS::$currentController.'/'.CMS::$currentAction,  $menu['selected'])?'active':null;
			$html.= '<div class="menu_icon_wrapper '.$class.'">
		                <a href="'.self::create_url($menu['selected'][0]).'" class="tooltip-right" data-tooltip="'.CMS::t('menu_item_'.str_replace('/', '_',$menu['selected'][0])).'">
		                	<i class="fa fa-'.$menu['icon'].'" aria-hidden="true"></i>
		                </a>
		            </div>';
		}

		return $html;
	}
}

view::$pagination_defaults = [
	'total' => 1,												// how many pages total
	'current' => 1,												// what is current page
	'hide_if_only' => true,										// don't show if only one page
	'delimiter' => '',											// what is between pagination items
	'page_url' => '%d',											// link: http://www.example.com/en/news/page_%d/
	'container_tagname' => '',									// overall pagination container, easily can be defined from template
	'class' => '',												// pagination container class
	'id' => '',													// pagination container id
	'inner_container_tagname' => '',							// container tagname of number <a><{tagname}>1</{tagname}></a>
	'active_container_tagname' => 'span',						// container tagname instead of link for active number
	'scope' => true,											// enable hiding of unnecessary items (<< < 2 3 4 > >>)
	'scope_width' => 11,										// how many numbers to show
	'act_class' => 'number current',							// active number container class <b class="{class}">1</b>
	'act_id' => '',												// active number container id
	'number_class' => 'number',
	'scope_start_text' => '&laquo; '.CMS::t('pg_2start').' ',	// &lArr;
	'scope_start_title' => '',									// title of link <a href="#div_1" title="{title}">To begin</a>
	'scope_start_class' => '',
	'scope_end_text' => ' '.CMS::t('pg_2end').' &raquo;',		// &rArr;
	'scope_end_title' => '',
	'scope_end_class' => '',
	'scope_prev_text' => '&lsaquo; '.CMS::t('pg_prev').' ',		// &larr;
	'scope_prev_title' => '',
	'scope_prev_class' => '',
	'scope_next_text' => ' '.CMS::t('pg_next').' &rsaquo;',		// &rarr;
	'scope_next_title' => '',
	'scope_next_class' => ''
];

?>