<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;
use app\views\widgets\HTML;

if (!defined("_VALID_PHP")) die('Direct access to this location is not allowed.');

?>


<style>
.image-preview {
	position: relative; margin-bottom: 2px;
}
.image-preview-img {
	border-radius: 3px;
	max-height:380px;
	width:50%;
}
.image-preview-overlay {
	display: none; position: absolute; left: 0; top: 0; z-index: 100; background: #000; opacity: 0.5;
}
.image-preview-info {
	display: none; position: absolute; left: 0; top: 0; z-index: 101; padding: 20px 30px;
}
.image-preview-info p {
	margin: 0; padding: 0; color: #fff; line-height: 20px;
}
.image-preview-info a {
	font-size: 16px; color: #fff; margin-right: 30px;
}
.image-preview-info a:hover {
	text-decoration: underline;
}
</style>


<?php

// load resourses

// load jQuery UI (for autocomplete)
view::appendCsS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-ui-1.12.1/jquery-ui.css');
view::prependJS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-ui-1.12.1/jquery-ui.min.js');

// load Bootstrap Datepicker
view::appendCsS(SITE_DIR.CMS_DIR.JS_DIR.'bootstrap-datepicker/css/bootstrap-datepicker3.css');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'bootstrap-datepicker/js/bootstrap-datepicker.min.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'bootstrap-datepicker/locales/bootstrap-datepicker.'.$_SESSION[CMS::$sess_hash]['ses_adm_lang'].'.min.js');

//MagicSuggest
view::appendCsS(SITE_DIR.CMS_DIR.CSS_DIR.'magicsuggest-min.css');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'magicsuggest-min.js');

// load CK Editor
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'ckeditor/ckeditor.js');


?>
<script type="text/javascript">
// <![CDATA[

$(document).ready(function() {
	$('.image-preview').append('<div class="image-preview-overlay"></div>');
	$('.image-preview').hover(function() {
		var $overlay = $('.image-preview-overlay', this);
		var $info = $('.image-preview-info', this);
		var $img = $('img', this);
		$overlay.show();
		$info.show();
		$overlay.height($img.outerHeight());
		$overlay.width($img.outerWidth());
	}, function() {
		var $overlay = $('.image-preview-overlay', this);
		var $info = $('.image-preview-info', this);
		$overlay.hide();
		$info.hide();
	});

	utils.setConfirmation('click', '#aDelImg', '<?=CMS::t('delete_confirmation');?>', function($el) {
		$.ajax({
			url: $el.attr('href'),
			async: true,
			cache: false,
			dataType: 'json',
			method: 'post',
			data: {
				news_id: '<?=$news['id'];?>',
				CSRF_token: '<?=$CSRF_token;?>'
			},
			success: function(response, status, xhr) {
				if (response.success) {
					utils.alert('<?=utils::safeJsEcho(CMS::t('del_suc'), 1);?>', function() {
						location.href = location.href;
					});
				} else {
					utils.alert('<?=utils::safeJsEcho(CMS::t('del_err'), 1);?>');
				}
			},
			error: function(xhr, err, descr) {
				utils.alert(err+' '+descr);
			}
		});

		return false;
	});
	
	/* 
	$('[name="gallery_name"]').autocomplete({
		source: function(request, response_callback) {
			$.ajax({
				url: '?controller=gallery&action=ajax_get_autocomplete&q='+request.term,
				async: true,
				cache: false,
				dataType: 'json',
				success: function(response, status, xhr) {
					if (response.success) {
						response_callback(response.data);
					} else {
						response_callback([]);
					}
				},
				error: function(xhr, err, descr) {
					response_callback([]);
				}
			});
		},
		minLength: 2,
		change: function(event, ui) {
			$('[name="gallery_id"]').val(ui.item? ui.item.id: '');
		},
		select: function(event, ui) {
			$('[name="gallery_id"]').val(ui.item? ui.item.id: '');
		}
	});
	$('[name="gallery_name"]').focus(function() {
		this.value = '';
	});

	
	function checkIn(array, value){
		for(x in array){
			if(array[x].name == value) return false;
		}
		return true;
	}

	var ms = $('#magicsuggest').magicSuggest({
        value: JSON.parse('<?=$selection_tags?>'),
        data: JSON.parse('<?=$all_tags?>'),
        name: 'tags',
        placeholder: '<?=CMS::t('news_keywords_select')?>'
    });

     $(ms).on('blur', function(e,m){
     	
   		if(this.getSelection().length){
			_this = this;
   			_value = this.getSelection()[this.getSelection().length-1].name;

			if(checkIn(this.getData(), _value)){
				$.get('<?=view::create_url('keywords', 'add_by_value')?>', {value: _value}, function(response){
		  			if(response.success){
		  				_datas = _this.getData();
		  				_new_data = {id:response.data, name: _value};
		  				_datas.unshift(_new_data);
		  				_this.setData(_datas);
		  				ms.setValue([response.data]);
		  				$('.ms-sel-item:contains("'+_value+'"):first').find('.ms-close-btn').trigger('click');	
		  			}
		  		});
	    	}
   		}
    
	}); */
	
	
	
});
// ]]>
</script>


<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?=CMS::t('menu_item_news_edit');?>
		<small><?=utils::safeEcho(utils::limitStringLength((empty($news['translates'][CMS::$default_site_lang]['title'])? $news['sef']: $news['translates'][CMS::$default_site_lang]['title']), 48), 1);?></small>
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

		<?php if ($comments_num && CMS::hasAccessTo('comments/list')) { ?>
		<a href="?controller=comments&amp;action=list&amp;filter[ref_table]=news&amp;filter[ref_id]=<?=$news['id'];?>&amp;<?=time();?>" class="btn btn-default"><i class="fa fa-commenting" aria-hidden="true"></i> <?=CMS::t('menu_item_comments_list');?> (<?=$comments_num;?>)</a>
		<?php } ?>
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

	<div class="box" style="border-top: none;">
		<!-- <div class="box-header with-border">
			<h3 class="box-title"><?=CMS::t('menu_item_news_edit');?></h3>
		</div> -->
		<!-- /.box-header -->

		<form action="" method="post" enctype="multipart/form-data" class="form-std" role="form">
			<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />

			<div class="box-body" style="padding: 0;">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<?php if (!empty($langs) && is_array($langs)) foreach ($langs as $k=>$lng) {?>
							<li <?=$lng['language_dir']==CMS::$default_site_lang?'class="active"':null?>><a data-tab="true" href="#desc-tab-<?=$lng['language_dir']?>"><?=$lng['language_name']?></a></li>
						<?php } ?>
						<li><a data-tab="true" href="#common-tab"><?=CMS::t('common_data')?></a></li>
					</ul>
					<div class="tab-content">
						<!-- Common Informational TAB -->
						<?php
							if (!empty($langs) && is_array($langs)) foreach ($langs as $lng) { //if($lng['language_dir'] !=  CMS::$default_site_lang) break;
						?>
						<div id="desc-tab-<?=$lng['language_dir']?>" class="tab-pane">
							<!-- <?=$lng['language_name'];?> tab -->

							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label><?=CMS::t('news_title');?> <?=(($lng['language_dir']==CMS::$default_site_lang)? '*': '');?></label>

												<input type="text" name="title[<?=$lng['language_dir'];?>]" value="<?=utils::safeEcho((isset($_POST['title'][$lng['language_dir']])? $_POST['title'][$lng['language_dir']]: @$news['translates'][$lng['language_dir']]['title']), 1);?>" class="form-control" />
											</div>
										</div>
									</div>

									<div class="form-group">
										<label><?=CMS::t('image');?></label>

										<?php if (!empty($news['translates'][$lng['language_dir']]['img'])) {
												$uploadUrl = SITE.utils::dirCanonicalPath(CMS_DIR.UPLOADS_DIR);
												$previewUrl = $uploadUrl.'news/originals/'.$news['translates'][$lng['language_dir']]['img'];
												$preview_exists = is_file(UPLOADS_DIR.'news/originals/'.$news['translates'][$lng['language_dir']]['img']);
											?>
										<div class="image-preview">
											<img src="<?=($preview_exists? $previewUrl: IMAGE_DIR.'noimg.jpg');?>" alt="<?=$news['translates'][$lng['language_dir']]['img']?>" class="img-responsive image-preview-img" />
											<div class="image-preview-info">
												<p>
												<?php
													$orgSize = utils::getFileSizeFormatted(UPLOADS_DIR.'news/originals/'.$news['translates'][$lng['language_dir']]['img']);
													$tmbSize = utils::getFileSizeFormatted(UPLOADS_DIR.'news/thumbs/'.$news['translates'][$lng['language_dir']]['img']);
													$imgModTimestamp = @filemtime(UPLOADS_DIR.'news/originals/'.$news['translates'][$lng['language_dir']]['img']);
												?>
												<?=CMS::t('news_image_original_size');?>: <?=$orgSize['value'];?> <?=$orgSize['measure'];?><br />
												<?=CMS::t('news_image_thumbnail_size');?>: <?=$tmbSize['value'];?> <?=$tmbSize['measure'];?><br />
												<?=CMS::t('news_image_original_upload_datetime');?>: <?=($imgModTimestamp? date('d.m.Y H:i:s', $imgModTimestamp): '-');?><br />
												<br />
												<br />
												<?php if ($preview_exists) { ?>
												<a href="<?=$previewUrl;?>" target="_blank"><img src="<?=IMAGE_DIR;?>eye_white.png" alt="" style="height: 16px;" /> <?=CMS::t('news_image_original_view');?> <img src="<?=IMAGE_DIR;?>outer_link_white.png" alt="" /></a>
												<?php } ?>
												<?php if (CMS::hasAccessTo('news/ajax_delete_image', 'write')) { ?>
												<a href="?controller=news&amp;action=ajax_delete_image" id="aDelImg"><img src="<?=IMAGE_DIR;?>drop_white.png" alt="" style="height: 16px;" /> <?=CMS::t('delete');?></a>
												</p>
												<?php } ?>
											</div>
										</div>
										<?php } ?>

										<?=view::browse([
											'name' => 'img['.$lng['language_dir'].']',
											'accept' => 'image/*'
										]);?>
										
										<input type="hidden" name="img[<?=$lng['language_dir']?>]" value="<?=$news['translates'][$lng['language_dir']]['img']?>" />

										<p class="form-info-tip"><?=CMS::t('news_image_descr', [
											'{types}' => implode(', ', $allowed_thumb_ext)
										]);?></p>
									</div>

									<div class="form-group">
										<label><?=CMS::t('news_full');?> <?=(($lng['language_dir']==CMS::$default_site_lang)? '*': '');?></label>

										<textarea name="full[<?=$lng['language_dir'];?>]" rows="4" cols="32" class="form-input-std" id="wysiwyg_full_<?=$lng['language_dir'];?>"><?=utils::safeEcho((isset($_POST['full'][$lng['language_dir']])? $_POST['full'][$lng['language_dir']]: @$news['translates'][$lng['language_dir']]['full']), 1);?></textarea>
										<script type="text/javascript">
// <![CDATA[
CKEDITOR.replace('wysiwyg_full_<?=$lng['language_dir'];?>', {
	uiColor: '#f9f9f9',
	language: '<?=$lng['language_dir'];?>',
	filebrowserBrowseUrl: '<?=SITE.CMS_DIR.JS_DIR?>filemanager/dialog.php?hash=<?=CMS::$sess_hash?>',
	filebrowserUploadUrl: '<?=SITE.CMS_DIR.JS_DIR?>filemanager/dialog.php?hash=<?=CMS::$sess_hash?>',
	height:200
});
// ]]>
										</script>
									</div>
								</div>
							</div>
						</div>
						<?php
							}
						?>
						
						<div class="tab-pane active" id="common-tab">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="id-number"><?=CMS::t('news_id_num')?>: &nbsp; <?=$news['id']?></label>
									</div>
									<div class="form-group">
										<label><a href="https://google.com/?q=SEF+friendly+urls" title="" target="_blank"><?=CMS::t('news_sef');?> <i class="fa fa-external-link" aria-hidden="true" style="font-size: inherit;"></i></a></label>

										<input type="text" name="sef" value="<?=utils::safePostValue('sef', $news['sef'], 1);?>" class="form-control" />
										<!-- <p class="form-input-tip"><?=CMS::t('news_sef_descr');?></p> -->
									</div>


									<div class="form-group">
										<label><?=CMS::t('news_add_datetime');?></label>
										<div class="row">
											<div class="col-xs-6">
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" name="add_date" value="<?=utils::safePostValue('add_date', utils::formatMySQLDate('d.m.Y', $news['add_datetime']), 1);?>" placeholder="<?=CMS::t('news_publish_date_placeholder');?>" class="form-control datepicker" />
												</div>

												<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	$('[name="add_date"]').datepicker({
		format: 'dd.mm.yyyy',
		clearBtn: true,
		language: '<?=utils::safeJsEcho($_SESSION[CMS::$sess_hash]['ses_adm_lang'], 1);?>'
	});
});
// ]]>
												</script>
											</div>

											<div class="col-xs-6">
												<select name="add_hour" class="form-control" style="display: inline-block; width: 45%;">
													<option value=""><?=CMS::t('news_publish_hour_placeholder');?></option>
													<?php
														$publish_hour_selected = (empty($_POST['add_hour'])? utils::formatMySQLDate('G', $news['add_datetime']): $_POST['add_hour']);
														for ($i=0; $i<24; $i++) {
															?><option value="<?=$i;?>"<?=(($i==$publish_hour_selected)? ' selected="selected"': '');?>><?=str_pad($i, 2, '0', STR_PAD_LEFT);?></option><?php
														}
													?>
												</select>
												:
												<select name="add_minutes" class="form-control" style="display: inline-block; width: 45%;">
													<option value=""><?=CMS::t('news_publish_minutes_placeholder');?></option>
													<?php
														$publish_minutes_selected = (empty($_POST['add_minutes'])? intval(utils::formatMySQLDate('i', $news['add_datetime'])): $_POST['add_minutes']);
														if ($publish_minutes_selected>=60) {
															$publish_minutes_selected = 0;
														}
														for ($i=0; $i<60; $i=($i+5)) {
															?><option value="<?=$i;?>"<?=(($i==$publish_minutes_selected)? ' selected="selected"': '');?>><?=str_pad($i, 2, '0', STR_PAD_LEFT);?></option><?php
														}
													?>
												</select>
											</div>
										</div>
									</div>

									<div class="form-group">
										<label><?=CMS::t('news_publish_datetime');?></label>

										<div class="row">
											<div class="col-xs-6">
												<div class="input-group">
													<div class="input-group-addon"><i class="fa fa-calendar"></i></div>
													<input type="text" name="publish_date" value="<?=utils::safePostValue('publish_date', utils::formatMySQLDate('d.m.Y', $news['publish_datetime']), 1);?>" placeholder="<?=CMS::t('news_publish_date_placeholder');?>" class="form-control datepicker" />
												</div>

												<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	$('[name="publish_date"]').datepicker({
		format: 'dd.mm.yyyy',
		clearBtn: true,
		language: '<?=utils::safeJsEcho($_SESSION[CMS::$sess_hash]['ses_adm_lang'], 1);?>'
	});
});
// ]]>
												</script>
											</div>

											<div class="col-xs-6">
												<select name="publish_hour" class="form-control" style="display: inline-block; width: 45%;">
													<option value=""><?=CMS::t('news_publish_hour_placeholder');?></option>
													<?php
														$publish_hour_selected = (empty($_POST['publish_hour'])? utils::formatMySQLDate('G', $news['publish_datetime']): $_POST['publish_hour']);
														for ($i=0; $i<24; $i++) {
															?><option value="<?=$i;?>"<?=(($i==$publish_hour_selected)? ' selected="selected"': '');?>><?=str_pad($i, 2, '0', STR_PAD_LEFT);?></option><?php
														}
													?>
												</select>
												:
												<select name="publish_minutes" class="form-control" style="display: inline-block; width: 45%;">
													<option value=""><?=CMS::t('news_publish_minutes_placeholder');?></option>
													<?php
														$publish_minutes_selected = (empty($_POST['publish_minutes'])? intval(utils::formatMySQLDate('i', $news['publish_datetime'])): $_POST['publish_minutes']);
														if ($publish_minutes_selected>=60) {
															$publish_minutes_selected = 0;
														}
														for ($i=0; $i<60; $i=($i+5)) {
															?><option value="<?=$i;?>"<?=(($i==$publish_minutes_selected)? ' selected="selected"': '');?>><?=str_pad($i, 2, '0', STR_PAD_LEFT);?></option><?php
														}
													?>
												</select>
											</div>
										</div>
									</div>

									<div class="form-group">
										<?php
											$is_published = (isset($_POST['CSRF_token'])? @$_POST['is_published']: $news['is_published']);
										?>
										<input type="checkbox" name="is_published" value="1"<?=($is_published? ' checked="checked"': '');?> id="triggerNewsStatus" /><label for="triggerNewsStatus" style="display: inline; font-weight: normal;"> <?=CMS::t('publish');?></label>
									</div>
								</div>

								<div class="col-md-6">
									<?php if (!empty($cats) && count($cats)) { ?>
									<div class="form-group">
										<label><?=CMS::t('news_category');?></label>

										<div class="form-div-multicheckbox" style="width: 100%; height: auto; max-height: 300px;">
											<?=HTML::renderTree($cats, $art_cats)?>
										</div>
									</div>
									<?php } ?>

									<?php /* if(isset($gallery)){?>
										<div class="form-group">
											<label><?=CMS::t('news_gallery');?></label>
											<select name="gallery_id" class="form-control select2" id="selectGalleryPicker">
												<option value=""><?=CMS::t('choose_gallery')?></option>  
												<?php foreach($gallery as $gal){
													$select_id = isset($_POST['gallery_id'])?$_POST['gallery_id']:$news['gallery_id'];
												?>
													<option <?=$gal['id'] == $select_id?'selected="selected"':null?> value="<?=$gal['id'];?>" ><?=$gal['name']?></option>  
												<?php }?>
											</select>
										</div>
									<?php } */?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->

			<?php if ($canWrite) { ?>
			<div class="box-footer">
				<button type="submit" name="save" value="1" class="btn btn-primary"><i class="fa fa-save" aria-hidden="true"></i> <?=CMS::t('save');?></button>
				<button type="submit" name="apply" value="1" class="btn btn-success"><i class="fa fa-check" aria-hidden="true"></i> <?=CMS::t('apply');?></button>
				<button type="reset" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh" aria-hidden="true"></i> <?=CMS::t('reset');?></button>
			</div>
			<?php } ?>
		</form>
	</div>
	<!-- /.box -->

	<!-- /.info boxes -->
</section>
<!-- /.content -->