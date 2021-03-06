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
			<?=CMS::t('menu_item_videos_add');?>
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

			<form action="" method="post" enctype="multipart/form-data" class="form-std" role="form">
				<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />

				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label><?=CMS::t('videos_link');?> *</label>

								<div class="link-tabs">
									<a href="tab-image">
										<i class="fa fa-video-camera" aria-hidden="true"></i>
									</a>
									<a href="tab-video">
										<i class="fa fa-youtube" aria-hidden="true"></i>
									</a>
								</div>
								
								<div class="div-tabs tab-image">
									<input type="file" name="link" class="form-control" autocomplete="off" />
								</div>
								 
								<div class="div-tabs tab-video">
									<label><?= CMS::t('video'); ?></label>
									<input placeholder="http://www.youtube.com/watch?v=bQr3H3ToW-Q" type="text" name="link" value="<?=utils::safeEcho(@$_POST['link'], 1);?>" class="form-control">
								</div>
							</div>
							
							<div class="form-group">
    							<label><?=CMS::t('videos_title');?> *</label>
    							<input type="text" name="title" class="form-control" autocomplete="off" />
    						</div>

    						 <div class="form-group">
								<label><?=CMS::t('videos_category');?></label>
								
								<div class="form-div-multicheckbox" style="width: 100%; height: auto; max-height: 214px;">
								    <?=HTML::renderTree($cats, [], true)?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

				<div class="box-footer">
					<button type="submit" name="add" value="1" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?=CMS::t('add');?></button>
					<a href="<?=utils::safeEcho($link_back, 1);?>"><button type="button" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh" aria-hidden="true"></i> <?=CMS::t('reset');?></button></a>
				</div>
			</form>
		</div>
		<!-- /.box -->

		<!-- /.info boxes -->
	</section>
	<!-- /.content -->