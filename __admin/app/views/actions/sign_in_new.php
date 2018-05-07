<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

view::appendCsS(SITE_DIR.CMS_DIR.CSS_DIR.'antique/login.css');

?>
<body>
	<div id="page">
		<div id="nonfooter">
			<header>
				<div id="logo"></div>
				<h3><?=CMS::t('project_name')?></h3>
			</header>

			<div id="content">	

				<?php if (!empty($response['errors'])) foreach ($response['errors'] as $e) { ?>
					<div class="callout callout-danger">
						<!-- <h4></h4> -->
						<p><?=CMS::t($e);?></p>
					</div>
				<?php } ?>

				<form method="post" action="" autocomplete="off">
					<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />
					<div class="form_row align_center">
						<input name="ad_login" type="text" tabindex="1" value="<?=utils::safeEcho(@$_POST['ad_login'], 1);?>" placeholder="<?=CMS::t('login_placeholder');?>" class="form_input" />
					</div>

					<div class="form_row align_center">
						<input name="ad_password" type="password" tabindex="2"  value="" placeholder="<?=CMS::t('login_password_placeholder');?>" class="form_input " />
					</div>

					<div class="form_controls">
						<input name="ad_send" type="submit" tabindex="3" value="<?=CMS::t('login_sign_in');?>" id="btnLogin" />
					</div>
				</form>
			</div>
		</div>
	</div>

		<footer>
			<div class="footer_wrap">
				<p>&copy; 2017 <?=CMS::t('footer_text')?></p>
			</div>
		</footer>
</body>

