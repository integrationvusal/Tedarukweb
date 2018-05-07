<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

?>


	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?=CMS::t('menu_item_banners_edit');?>
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
								<label><?=CMS::t('banners_link');?> *</label>
								<input type="text" name="link" value="<?=isset($_POST['link'])?utils::safeEcho($_POST['link'], 1):$data['link'];?>" class="form-control" autocomplete="off" />
							</div>
							<div class="form-group">
								<label><?=CMS::t('banners_title');?> *</label>
								<input type="text" name="title" value="<?=isset($_POST['title'])?utils::safeEcho($_POST['title'], 1):$data['title'];?>" class="form-control" autocomplete="off" />
							</div>
							<div class="form-group">
								<label><?=CMS::t('banners_img');?> *</label>
								
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<?foreach(CMS::$site_langs as $k=>$lang):?>
											<li class="<?if($k==0):?>active<?endif?>"><a data-tab="true" href="#img-tab-<?=$lang['language_dir']?>"><?=$lang['language_name']?></a></li>
										<?endforeach?>
									</ul>
									
									<div class="tab-content">
										<?foreach(CMS::$site_langs as$lang):?>
											<div id="img-tab-<?=$lang['language_dir']?>" class="tab-pane <?if($k==0):?>active<?endif?>">
											
										<img width=100 src="<?=SITE_DIR?>upload/banners/<?=isset($data['translates'][$lang['language_dir']]['img'])?$data['translates'][$lang['language_dir']]['img']:null?>"/>
										
										<input type="file" name="img[<?=$lang['language_dir']?>]" class="form-control" autocomplete="off" />
										
									    <input type="hidden" name="img[<?=$lang['language_dir']?>]" value="<?=isset($data['translates'][$lang['language_dir']]['img'])?$data['translates'][$lang['language_dir']]['img']:null;?>" class="form-control" autocomplete="off" />
									    
											</div>
										<?endforeach?>
									</div>
								</div>
								
							</div>
						</div>
					</div>
				</div>
				<!-- /.box-body -->

				<div class="box-footer">
					<button type="submit" name="edit" value="1" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?=CMS::t('edit');?></button>
					<a href="<?=utils::safeEcho($link_back, 1);?>"><button type="button" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh" aria-hidden="true"></i> <?=CMS::t('reset');?></button></a>
				</div>
			</form>
		</div>
		<!-- /.box -->

		<!-- /.info boxes -->
	</section>
	<!-- /.content -->