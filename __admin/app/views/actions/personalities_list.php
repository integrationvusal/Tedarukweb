<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

view::appendCSS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-ui-1.12.1/jquery-ui.min.css');
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'jquery-ui-1.12.1/jquery-ui.min.js');

?>

<style>
.keyword-selected{ width: 98.35% !important; background-color:#03a9f4 !important; color:#fff;}
.keyword-selected td{ float:left; width: 53.65% !important; }
.keyword-selected td + td{ float:left; width: 46.35% !important; }


td {
	vertical-align: middle !important;
}
</style>


<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
	/*$('#filter-button').on('click', function() {
		$.fancybox.open({
			href: '#popupFilter'
		});
	});*/

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

	$('#tablesorter').sortable({
		start: function(e, ui){
			//ui.item.addClass('keyword-selected');
		},
		beforeStop:function(e, ui){
			//ui.item.removeClass('keyword-selected');
		},
		update: function(e, ui){

			_items 		=   [];
			_cal_start  =   $('#tablesorter').data('page') * $('#tablesorter').data('limit');

			$('tr',e.target).each(function(i){
				_items[i] =  $(this).data('id');
			});

			$.ajax({
				url: "<?=view::create_url('personalities', 'ajax_sort')?>",
				async: false,
				cache: false,
				method: 'POST',
				data: {
					CSRF_token: '<?=$CSRF_token;?>',
					cal_start: _cal_start,
					items: _items
				},
				dataType: 'json',
				success: function(response, status, xhr) {
					console.log(response)
					if (!response.success)	$('#tablesorter').sortable('cancel');
				},
				error: function(xhr, err, descr) {
					$('#tablesorter').sortable('cancel');
				}
			});
		},
        	items: "tr",
	});

});
// ]]>
</script>


	<!-- Deleting hidden form -->
	<form action="<?=view::create_url('personalities', 'delete') ?>" method="post" id="formDeleteItem">
		<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />
		<input type="hidden" name="delete" value="0" />
	</form>


	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			<?=CMS::t('menu_item_personalities_list');?>
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
			<?php if (CMS::hasAccessTo('personalities/add', 'write')) { ?>
			<a href="<?=view::create_url('personalities', 'add', ['return' => $link_return]) ?>&amp;<?=time();?>" class="btn btn-default"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?=CMS::t('menu_item_personalities_add');?></a>
			<?php } ?>
		</nav>
	</section>

	<!-- Main content -->
	<section class="content">

		<!-- Info boxes -->

		<!-- <pre><?php /*var_export($users);*/ ?></pre> -->

		<div class="box">
			<div class="box-header with-border">
				<h3 class="box-title"><?=CMS::t('personalities_list_details', [
					'{count}' => $count,
					'{ru:u1}' => utils::getRussianWordEndingByNumber($count, '', 'ы', 'о'),
					'{ru:u2}' => utils::getRussianWordEndingByNumber($count, 'ь', 'я', 'ей')
				]);?></h3>

				<div class="box-tools pull-right col-sm-5 col-lg-6">
					<form action="" method="get" id="formSearchAndFilter">
						<input type="hidden" name="controller" value="<?=utils::safeEcho(@$_GET['controller'], 1);?>" />
						<input type="hidden" name="action" value="<?=utils::safeEcho(@$_GET['action'], 1);?>" />
						<input type="hidden" name="<?=time();?>" value="" />

						<div class="input-group has-feedback">
							<input type="text" name="q" value="<?=utils::safeEcho(@$_GET['q'], 1);?>" placeholder="<?=CMS::t('personalities_search');?>" class="form-control input-md" onfocus="this.value='';" />
							<span class="input-group-btn">
								<button type="submit" name="go" value="1" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> <?=CMS::t('search');?></button>
							</span>
						</div>
					</form>
				</div>
			</div>
			<!-- /.box-header -->

            <div class="box-body">
				<?php
					if (!empty($alldata) && is_array($alldata)) {
				?>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th><?=CMS::t('personalities_title');?></th>
							<th><?=CMS::t('personalities_fio');?></th>
							<th><?=CMS::t('personalities_birth');?></th>
							<th><?=CMS::t('personalities_deadth');?></th>
							<th><?=CMS::t('controls');?></th>
						</tr>
					</thead>
					<tbody id="tablesorter" data-page="<?=$current-1?>" data-limit="<?=$limit?>">
						<?php
							foreach ($alldata as $data) {
						?>
							<tr data-id="<?=$data['id']?>">
								<td><?=$data['title']?></td>
								<td><?=$data['fio']?></td>
								<td><?=$data['birth']?></td>
								<td><?=$data['deadth']?></td>
								<td>
									<?php if (CMS::hasAccessTo('personalities/edit', 'write')) { ?>
										<a href="<?=view::create_url('personalities', 'edit', ['id'=>$data['id']])?>" title="<?=CMS::t('edit');?>">
											<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
										</a>
									<?php } ?>

									<?php if (CMS::hasAccessTo('personalities/delete', 'write')) { ?>
									<a href="#" title="<?=CMS::t('delete');?>" class="text-red" style="margin-left: 15px;" id="aDeleteItem_<?=$data['id'];?>" data-item-id="<?=$data['id'];?>">
										<i class="fa fa-trash" aria-hidden="true"></i>
									</a>
									<script type="text/javascript">
										utils.setConfirmation('click', '#aDeleteItem_<?=$data['id'];?>', '<?=CMS::t('delete_confirmation');?>', function($el) {
											var id = $el.attr('data-item-id');
											var $form = $('#formDeleteItem');
											$('[name="delete"]', $form).val(id);
											$form.submit();
										});
									</script>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
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