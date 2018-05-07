<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}
?><!DOCTYPE html>
<html lang="<?=isset($_SESSION[CMS::$sess_hash])?$_SESSION[CMS::$sess_hash]['ses_adm_lang']:CMS::$default_site_lang;?>">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

		<title><?=utils::safeEcho(CMS::$site_settings['cms_name'], 1);?> - <?=utils::safeEcho(self::$title, 1);?></title>

<?php

view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'custom-skin.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'admin-lte-2.3.7/skins/skin-new.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'admin-lte-2.3.7/AdminLTE.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'font-awesome-4.7.0/font-awesome.css');
view::prependCsS(SITE_DIR.CMS_DIR.CSS_DIR.'bootstrap-3.3.7/bootstrap.min.css');

view::appendCss(SITE_DIR.CMS_DIR.JS_DIR.'fancybox/jquery.fancybox.css');

print view::outputCssList();

?>

		<script type="text/javascript">
// <![CDATA[
var t = <?=json_encode(CMS::$lang);?>;
// ]]>
		</script>

<?php

view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'admin-lte-2.3.7/app.min.js');
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'jquery.slimscroll.min.js');
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'fastclick.min.js');
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'bootstrap-3.3.7/bootstrap.min.js');
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-2.2.3.min.js');

view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'fancybox/jquery.fancybox.pack.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'utils.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'custom-skin.js');

print view::outputJsList();

?>
		<script type="text/javascript">
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

	<?=$content;?>
</html>