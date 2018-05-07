<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}


view::appendCSS(SITE_DIR.CMS_DIR.JS_DIR.'jstree/themes/custom/style.css');
view::appendCSS(SITE_DIR.CMS_DIR.CSS_DIR.'nav.css');
view::appendCSS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-ui-1.12.1/jquery-ui.min.css');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'jstree/jstree.min.js');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-ui-1.12.1/jquery-ui.min.js');

?>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {

	$('#filter-button').on('click', function() {
		$.fancybox.open({
			href: '#popupFilter'
		});
	});

	$('#popupFilterClose').on('click', function() {
		$.fancybox.close();
		return false;
	});

	$('.aAjax').on('click', function() {
		var $el = $(this);
		var data = JSON.parse($(this).attr('data-ajax_post'));
		$.ajax({
			url: this.href,
			data: data,
			async: true,
			cache: false,
			type: 'post',
			dataType: 'json',
			success: function(response, status, xhr) {
				if (response.success) {
					if (response.data && response.data.action) {
						var new_status = response.data.action;
						var old_status = ((new_status=='on')? 'off': 'on');
						$('i', $el).removeClass('fa-toggle-'+old_status+' btn-toggle-'+old_status).addClass('fa-toggle-'+new_status+' btn-toggle-'+new_status);
						data.turn = old_status;
						$el.attr('data-ajax_post', JSON.stringify(data));
					}
				}
			},
			error: function(xhr, err, descr) {}
		});

		return false;
	});

	$('#divNavTree').jstree({
		'core': {
			'check_callback': function(operation, node, parent, position, more) {
				if (/*operation==="copy_node" ||*/ operation==="move_node") {
					if (parent.id==="#") {
						return false; // prevent moving a child above or below the root
					}
				}
				return true; // allow everything else
			}
		},
		'plugins': ['dnd']
	});
	$('#divNavTree').on('activate_node.jstree', function(node, event) {
		location.href = event.node.a_attr.href;
	});
	$('#divNavTree').on('move_node.jstree', function(node, event) {
		//console.log(event);
		_data = {
			CSRF_token: '<?=$CSRF_token;?>',
		};

		if(event.old_parent == event.parent) {
			_url = '<?=view::create_url('nav', 'set_position')?>';
		}else{
			_url = '<?=view::create_url('nav', 'set_parent')?>';
			_data['parent'] = {};
			_data['parent'][$('> a', '#'+event.node.id).data('item_id')] = $('> a', '#'+ event.parent).data('item_id');
		}

		_items = {};

		$('.jstree-children > li li').each(function(){
		    _items[ $('> a', this).data('item_id') ] = $(this).index();
		});

		_data['items'] = _items;

		$.ajax({
			url: _url,
			async: false,
			cache: false,
			method: 'POST',
			data: _data,
			dataType: 'json',
			success: function(response, status, xhr) {
				if (response.success) {
					// no action
				} else {
					// cancel
				}
			},
			error: function(xhr, err, descr) {
				// cancel
			}
		});
	});

	var switchType = function(type) {
		if ($.inArray(type, ['category', 'spec', 'article', 'contact','rehberlik'])!=-1) {
			$('[name="sef"]').parent().show();
		} else {
			$('[name="sef"]').parent().hide();
		}
	
		if ($.inArray(type, ['article'])!=-1) {
			$('[name="pseudo_ref_id"]').parent().show();
		} else {
			$('[name="pseudo_ref_id"]').parent().hide();
		}
		if (type=='url') {
			$('[name="url"]').parent().show();
		} else {
			$('[name="url"]').parent().hide();
		}
		if ($.inArray(type, ['category', 'spec'])!=-1) {
			$('[name="is_section"]').parent().show();
		} else {
			$('[name="is_section"]').parent().hide();
		}
	};

	$('[name="type"]').change(function() {
		switchType(this.value);
	});
	switchType($('[name="type"]').val());

	$('[name="pseudo_ref_id"]').autocomplete({
		source: function(request, response_callback) {
			$.ajax({
				url: '<?=view::create_url('nav', 'autocomplete')?>&q='+request.term,
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
		minLength: 2,  // !important
		open: function(){
			$('.ui-menu').width(360);
		},
		change: function(event, ui) {
			$('[name="ref_id"]').val(ui.item? ui.item.id: '');
		},
		select: function(event, ui) {
			$('[name="ref_id"]').val(ui.item? ui.item.id: '');
		}
	});


	$('[name="pseudo_ref_id"]').focus(function() {
		this.value = '';
	});
});
// ]]>
</script>

	<!-- Deleting hidden form -->
	<form action="?controller=cms_users&amp;action=delete" method="post" id="formDeleteItem">
		<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />
		<input type="hidden" name="delete" value="0" />
	</form>


	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?=CMS::t('menu_item_nav_list');?>
			<!-- <small>Subtitile</small> -->
		</h1>

		<!-- <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol> -->

	</section>

	<!-- Main content -->
	<section class="content">
		<?php if(isset($notice) && is_array($notice)){
			if(isset($notice['errors'])) print view::notice($notice['errors'], 'danger');
			elseif(isset($notice['message'])) print view::notice($notice['message'], 'success');
		}?>
		<?php if(isset($refresh)) print utils::delayedRedirect($refresh, 300); ?>

		<!-- Info boxes -->

		<!-- <pre><?php /*var_export($users);*/ ?></pre> -->

		<div class="box">

            <div class="box-body">
				<?php
					if (is_array($menu)) {
				?>

				<table class="table table-bordered table-striped">
					<tr>
						<td class="nav-tree-td">
							<div class="nav-tree" id="divNavTree">
								<ul>
									<li data-jstree="<?=utils::safeEcho(json_encode([
										'opened' => true,
										'selected' => empty($_GET['item']),
										//'icon' => 'templates/default/images/outer_link.png',
										'icon' => 'nav-role-root',
									]), 1);?>">
										<a data-item_id="0" href="<?=view::create_url('nav', 'list', ['page'=>$current])?>"><?=CMS::t('nav_menu');?></a>
										<?=$navTree?>
									</li>
								</ul>
							</div>
						</td>

						<td class="nav-item-td">
							<div class="nav-item">

								<!-- Action Buttons -->
								<?if ($canWrite && !$navAdd) {?>
									<div class="row">
										<a href="<?=view::create_url('nav', 'add', ['item'=>isset($item['id'])?$item['id']:0])?>" class="icoButton" style="margin: 0 0 20px 0;"><button type="button" class="btn btn-info"><span class="glyphicon glyphicon-plus-sign"></span>&nbsp;<?=CMS::t('nav_menu_add_item');?></button></a>
										<?php if (isset($item)) {?>

										<a href="<?=view::create_url('nav', 'delete', ['item'=>$item['id'], 'return' => view::create_url('nav', 'list', ['item' => $item['parent_id']]) ])?>" class="icoButton" id="aDeleteItem_<?=$item['id'];?>"><button type="button" class="btn btn-danger"><i class="fa fa-trash" aria-hidden="true"></i>&nbsp;<?=CMS::t('nav_menu_del_item');?></button></a>
										<script type="text/javascript">
											utils.setConfirmation('click', '#aDeleteItem_<?=$item['id'];?>', '<?=CMS::t('delete_confirmation');?>');
										</script>
										<?php } ?>
									</div>
								<?}?>

								<!-- Info Box -->
								<?php if (!empty($item['id']) && !$navAdd) {
									$url = '';
									switch ($item['type']) {
										case 'article':
											$article = articles::getArticle($item['ref_id']);
											$url.=SITE.CMS::$default_site_lang.'/'.$item['sef'].'/';
										break;
										case 'url':
											$url = strtr($item['url'], [
												'{lang}' => CMS::$default_site_lang
											]);
											
											if ($item['url'][0]=='#') {
												if ($url=='#') {
													$url = 'javascript:void(0);';
													//$url = '';
												}
											}
										break;

										default:
										$url = SITE.$item['type'].'/'.$item['sef'].'/';
									}
								?>
								<div class="row nav-panel-margin-top">
									<table class="table">
										<tr><th>URL</th><td><a href="<?=utils::safeEcho($url, 1);?>" target="_blank"><?=$url;?> <img src="<?=IMAGE_DIR;?>outer_link.png" alt="" /></a></td></tr>
										<tr><th><?=CMS::t('author');?></th><td>
											<a href="<?=view::create_url('cms_users', 'edit', ['id' => $item['add_by']])?>"><img src="<?=IMAGE_DIR;?>user_red.png" alt="" class="list-ico"/><?=utils::getOnlyWords($item['author_name']);?></a>, <?=utils::formatMySQLDate('d.m.Y H:i:s', $item['add_datetime']);?>
										</td></tr>
										<tr><th><?=CMS::t('editor');?></th><td>
											<?php if (!empty($item['mod_by'])) {?>
											<a href="<?=view::create_url('cms_users', 'edit', ['id' => $item['mod_by']])?>"><img src="<?=IMAGE_DIR;?>user_red.png" alt="" class="list-ico" /><?=utils::getOnlyWords($item['editor_name']);?></a>, <?=utils::formatMySQLDate('d.m.Y H:i:s', $item['mod_datetime']);?>
											<?php } ?>
										</td></tr>
									</table>
								</div>
								<?php } ?>


								<!-- Add / Edit / View Form -->

								<?php if ($navAdd || !empty($item['id'])) { ?>
								<form action="" class="row" method="post" enctype="multipart/form-data">
									<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />

									<h4 class="box-title"><i class="fa fa-tag"></i> <?=CMS::t('nav_menu_item_name');?></h4>

									<div class="nav-tabs-custom">
							            <ul class="nav nav-tabs">
							            	<?php foreach ($langs as $k=>$lng) { ?>
								              <li class="<?=$k==0?'active':null?>"><a href="#tab_<?=$k?>" data-toggle="tab" aria-expanded="false"><?=$lng['language_name']?></a></li>
							              	<?}?>
							            </ul>
							            <div class="tab-content">
							            	<? foreach ($langs as $k=>$lng) { ?>
								            	<div class="tab-pane <?=$k==0?'active':null?>" id="tab_<?=$k?>">
													<div class="form-group">
														<?php
														$name_value = '';
														if (!empty($_POST['CSRF_token'])) {
															$name_value = @$_POST['name'][$lng['language_dir']];
														} else if (!empty($item['id']) && !$navAdd) {
															$name_value = @$item['translates'][$lng['language_dir']]['name'];
														}
														?>
														<input type="text" name="name[<?=$lng['language_dir'];?>]" value="<?=utils::safeEcho($name_value, 1);?>" class="form-control form-innavput-std" />
													</div>

													<?php /*if ($lng['language_dir']!=CMS::$default_site_lang) { ?>
															<div class="input-group nav-panel-padding">

																<blockquote class="blockquote-box blockquote-info">
											  						<?php
																	$lng_published = false;
																	if (!empty($_POST['CSRF_token'])) {
																		$lng_published = !empty($_POST['is_published_lang'][$lng['language_dir']]);
																	} else if (!empty($item['id']) && !$navAdd) {
																		$lng_published = !empty($item['translates'][$lng['language_dir']]['is_published_lang']);
																	}
																	?>
																	<input type="checkbox" name="is_published_lang[<?=$lng['language_dir'];?>]" value="1"<?=($lng_published? ' checked="checked"': '');?> id="triggerLangStatus_<?=$lng['language_dir'];?>" /><label for="triggerLangStatus_<?=$lng['language_dir'];?>" style="display: inline; font-weight: normal;"> <?=CMS::t('publish_lang');?></label>
																</blockquote>
															</div>
													<?php } */?>
												</div>
											<?php } ?>
							            </div>
							         </div>


									<div class="form-group">
										<h4 class="box-title"><i class="fa fa-tag"></i> <?=CMS::t('nav_menu_item_type');?></h4>
										<select name="type" class="form-control form-input-std">
											<?php
												$type_selected = '';
												if (!empty($_POST['CSRF_token'])) {
													$type_selected = @$_POST['type'];
												} else if (!empty($item['id']) && !$navAdd) {
													$type_selected = $item['type'];
												}
												foreach ($navTypes as $navtype) {
													?><option value="<?=$navtype;?>"<?=(($navtype==$type_selected || ($navtype == 'category'))? ' selected="selected"': '');?>><?=CMS::t('nav_menu_item_type_'.$navtype);?></option><?php
												}
											?>
										</select>
										<select name="article_id" class="form-control form-input-std article-select">
											<option value=""><?=CMS::t('choose_article')?></option>
											<?php foreach($articles as $article){
												$selected = isset($_POST['article_id'])?$_POST['article_id']:$item['ref_id'];
											?>
												<option <?=$selected==$article['id']?'selected="selected"':null?> value="<?=$article['id']?>"><?=$article['title']?></option>
											<?php }?>
										</select>
									</div>

									<div class="form-group">
										<h4 class="box-title"><i class="fa fa-tag"></i> <?=CMS::t('nav_menu_item_sef');?></h4>
										<?php
											$sef_default = '';
											//$sef_default = utils::getuniqid();
											if (!empty($item['id']) && !$navAdd) {
												$sef_default = $item['sef'];
											}
										?>
										<input type="text" name="sef" value="<?=utils::safePostValue('sef', $sef_default, 1);?>" class="form-control form-input-std" />
									</div>

									<?php /*<div class="nav-panel-padding">
										<blockquote class="blockquote-box blockquote-info">
										  	<?php
										  		$is_section = false;
												if (!empty($item['id']) && !$navAdd) {
													$is_section = $item['is_section'];
												}
												$is_section = (isset($_POST['CSRF_token'])? @$_POST['is_section']: $is_section);
											?>
											<input type="checkbox" name="is_section" value="1"<?=($is_section? ' checked="checked"': '');?> id="triggerIsSection" />
											<label for="triggerIsSection" style="display: inline; font-weight: normal;"> <?=CMS::t('nav_menu_item_is_section');?></label>
										</blockquote>
									</div> */?>

									<div>
										<label><?=CMS::t('nav_menu_item_ref_article');?></label>
										<?php
											$artname_default = '';
											$art_id_default = '';
											if (!empty($item['id']) && !$navAdd) {
												$artname_default = @$article['translates'][CMS::$default_site_lang]['title'];
												$art_id_default = $item['ref_id'];
											}
										?>
										<input type="text" name="pseudo_ref_id" value="<?=utils::safePostValue('pseudo_ref_id', $artname_default, 1);?>" class="form-input-std autocomplete" />
										<input type="hidden" name="ref_id" value="<?=utils::safePostValue('ref_id', $art_id_default, 1);?>" />
									</div>

									<div>
										<label><?=CMS::t('nav_menu_item_url');?></label>
										<?php
											$url_default = '#';
											if (!empty($item['id']) && !$navAdd) {
												$url_default = $item['url'];
											}
										?>
										<input type="text" name="url" value="<?=utils::safePostValue('url', $url_default, 1);?>" class="form-control form-input-std" />
									</div>


									<div class="nav-panel-padding">
										<blockquote class="blockquote-box blockquote-info">
											<?php
												$is_published = true;
												if (!empty($item['id']) && !$navAdd) {
													$is_published = $item['is_published'];
												}
												$is_published = (isset($_POST['CSRF_token'])? @$_POST['is_published']: $is_published);
											?>
											<input type="checkbox" name="is_published" value="1"<?=($is_published? ' checked="checked"': '');?> id="triggerStatus" /><label for="triggerStatus" style="display: inline; font-weight: normal;"> <?=CMS::t('publish');?></label>
										</blockquote>
									</div>

									<div class="form-group">
										<?php if ($navAdd) { ?>
										<button type="submit" name="add" value="1" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span>&nbsp;<?=CMS::t('add');?></button>
										<button type="reset" name="cancel" value="1" class="btn btn-default" onclick="location.href='<?=utils::safeEcho(utils::safeJsEcho($link_sc, 1), 1);?>';"><i class="fa fa-refresh" aria-hidden="true"></i></span>&nbsp;<?=CMS::t('cancel');?></button>

										<?php } else if (!empty($item['id'])) { ?>
										<button type="submit" name="save" value="1" class="btn btn-primary"><i class="fa fa-save"></i>&nbsp;&nbsp;<?=CMS::t('save');?></button>
										<button type="submit" name="apply" value="1" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp; <?=CMS::t('apply');?></button>
										<button type="reset" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh" aria-hidden="true"></i>&nbsp;<?=CMS::t('reset');?></button>
										<?php } ?>
									</div>
								</form>
								<?php } ?>
							</div>
						</td>

					</tr>

					</table>



				<div class="pagination"><?=view::pg([
					'total' => $total,
					'current' => $current,
					'page_url' => $link_sc.'&amp;page=%d'
				]);?></div>
				<?php
					} else {
						print view::callout('', 'danger', 'no_data_found');
					}
				?>
			</div>
            <!-- /.box-body -->
		</div>
		<!-- /.box -->

		<!-- /.info boxes -->
	</section>
	<!-- /.content -->


<div id="popupFilter" style="display: none; width: 420px;">
	<h4 class="popupTitle"><?=CMS::t('filter_popup_title');?></h4>

	<div class="popupForm">
		<div class="popupFormFieldset">
			<div class="popupFormInputsBlock">
				<label for="selectRole" class="form-label"><?=CMS::t('cms_user_role');?></label>
				<select name="filter[role]" id="selectRole" class="form-control" form="formSearchAndFilter">
					<option value=""><?=CMS::t('filter_no_matter');?></option>
					<?php
					foreach (CMS::$roles as $role=>$allowed_pages) {
					?><option value="<?=$role;?>"<?=(($role==@$_GET['filter']['role'])? ' selected="selected"': '');?>><?=CMS::t($role);?></option><?php
					}
					?>
				</select>
			</div>

			<div class="popupFormInputsBlock">
				<label for="selectStatus" class="form-label"><?=CMS::t('access');?></label>
				<select name="filter[is_blocked]" id="selectStatus" class="form-control" form="formSearchAndFilter">
					<option value=""><?=CMS::t('filter_no_matter');?></option>
					<option value="1"<?=((@$_GET['filter']['is_blocked']=='1')? ' selected="selected"': '');?>><?=CMS::t('access_prohibited');?></option>
					<option value="0"<?=((@$_GET['filter']['is_blocked']==='0')? ' selected="selected"': '');?>><?=CMS::t('access_granted');?></option>
				</select>
			</div>
		</div>

		<div class="popupControls">
			<button type="submit" name="go" value="1" form="formSearchAndFilter" class="btn btn-primary center-block"><i class="fa fa-filter" aria-hidden="true"></i> <?=CMS::t('filter');?></button>
		</div>
	</div>
</div>