<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\security;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

?><!DOCTYPE html>
<html lang="<?=$_SESSION[CMS::$sess_hash]['ses_adm_lang'];?>">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

		<title><?=utils::safeEcho(CMS::$site_settings['cms_name'], 1);?> - <?=utils::safeEcho(self::$title, 1);?></title>

<?php

view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'antique/app.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'antique/reset.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'antique/style.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'admin-lte-2.3.7/AdminLTE.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'custom-skin.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'antique/darktooltip.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'antique/jquery-confirm.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'font-awesome-4.7.0/font-awesome.css');
view::appendCss(SITE_DIR.CMS_DIR.JS_DIR.'fancybox/jquery.fancybox.css');

print view::outputCssList();

?>

<script type="text/javascript">
// <![CDATA[
	var t = <?=json_encode(CMS::$lang);?>;

	var baseUrl = '<?=SITE?>';
	var lang = '<?=CMS::$default_site_lang?>';
	var _csrf = '<?=security::$CSRF_token?>';
// ]]>
</script>

<?php
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'bootstrap-3.3.7/bootstrap.min.js');
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-2.2.3.min.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'bootbox.min.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'antique/jquery-confirm.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'antique/translates/az.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'antique/app.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'utils.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'custom-skin.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'fancybox/jquery.fancybox.pack.js');

print view::outputJsList();

?>
		<script type="text/javascript">
			bootbox.addLocale('<?=utils::safeJsEcho($_SESSION[CMS::$sess_hash]['ses_adm_lang'], 1);?>', {
				OK: '<?=utils::safeJsEcho(CMS::t('js_ok'), 1);?>',
				CANCEL: '<?=utils::safeJsEcho(CMS::t('js_cancel'), 1);?>',
				CONFIRM: '<?=utils::safeJsEcho(CMS::t('js_confirm'), 1);?>',
			});
			bootbox.setLocale('<?=utils::safeJsEcho($_SESSION[CMS::$sess_hash]['ses_adm_lang'], 1);?>');
			$(document).ready(function() {
				$('.fancybox').fancybox();
			});
		</script>

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

	</head>
	<body>
		<div id="body-wrapper">
		    <a href="<?=SITE.CMS_DIR?>">
		    	<img src="<?=IMAGE_DIR;?>logo_az.png" alt="" id="logo"/>
		    </a>
		    
		    <a id="site_title" target="_blank" href="<?=SITE?>"><?=CMS::t( 'project_name')?></a>
		    
		    <div id="greeting">
		        Salam <b><?=ADMIN_INFO;?></b>
		        <a href="<?=view::create_url('base', 'sign_out')?>" title="<?=CMS::t('logout')?>">
		            <?=CMS::t( 'logout')?>
		        </a>
		    </div>
		    <div class="position_relative">
		        <div id="menu_icons_wrapper">
		        	<?=view::newmenu();?>
		        </div>
		    </div>

		    <div id="main-content">
		        <div class="content-box">
		        	<?=$content?>
		        </div>
		    </div>
	        
	        <div id="footer"></div>
	    </div>
	</body>
</html>