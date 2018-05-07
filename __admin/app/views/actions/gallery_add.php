<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;
use app\views\widgets\HTML;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

?>


<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?=CMS::t('menu_item_gallery_add');?>
		<!-- <small>Subtitile</small> -->
	</h1>

	<!-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> -->
</section>

<!-- Content Header (Page header) -->
<section class="contextual-navigation">
	<nav>
		<a href="<?=utils::safeEcho($link_back, 1);?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> <?=CMS::t('back');?></a>
	</nav>
</section>


<!-- Main content -->
<section class="content">
	<?php
		if (!empty($op)) {
			if ($op['success']) {
				print view::notice($op['message'], 'success');
			} else {
				print view::notice(empty($op['errors'])? $op['message']: $op['errors']);
			}
		}
	?>

	<!-- Info boxes -->

	<div class="box">
		<!-- <div class="box-header with-border">
			<h3 class="box-title"><?=CMS::t('menu_item_gallery_add');?></h3>
		</div> -->
		<!-- /.box-header -->

		<form action="" method="post" class="form-std" role="form">
			<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />

			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<?php foreach ($langs as $lng) { ?>
							<div class="form-group">
								<label><?=CMS::t('gallery_album_name');?> (<?=$lng['language_name'];?>)</label>

								<input type="text" name="name[<?=$lng['language_dir'];?>]" value="<?=utils::safeEcho(@$_POST['name'][$lng['language_dir']], 1);?>" class="form-control" />
							</div>
						<?php } ?>

                        <div class="form-group">
							<label><?=CMS::t('gallery_category');?></label>
							
							<div class="form-div-multicheckbox" style="width: 100%; height: auto; max-height: 214px;">
							    <?=HTML::renderTree($cats, [], true)?>
							</div>
						</div>

						<div class="form-group">
							<input type="checkbox" name="is_published" value="1"<?=((isset($_POST['is_published']) && empty($_POST['is_published']))? '': ' checked="checked"');?> id="triggerGalleryStatus" /><label for="triggerGalleryStatus" style="display: inline; font-weight: normal;"> <?=CMS::t('publish');?></label>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->

			<div class="box-footer">
				<button type="submit" name="add" value="1" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?=CMS::t('add');?></button>
				<button type="reset" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh" aria-hidden="true"></i> <?=CMS::t('reset');?></button>
			</div>
		</form>
	</div>
	<!-- /.box -->

	<!-- /.info boxes -->
</section>
<!-- /.content -->