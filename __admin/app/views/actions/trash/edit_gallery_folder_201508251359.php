<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

	$sfgf=mysql_query("SELECT * FROM `cs_gallery_folder` WHERE `gallery_folder_id`=".(int)$_GET['id']." LIMIT 1");
		$rfgf=mysql_fetch_assoc($sfgf);

	$sfl=mysql_query('SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC');
		while($rfl=mysql_fetch_assoc($sfl))	{

			$pt='folder_name_'.$rfl['language_dir'];
			$$pt= @$_POST['folder_name_'.$rfl['language_dir']];

		}
		
	$ok_folder=@$_POST['ok_folder'];
	$content_id=@$_POST['content_id'];
	$datetime=date('Y-m-d H:i:s');
	$err=0;
	
	global $errors;

	if(isset($ok_folder)){
	
		if(!$folder_name_az || !$folder_name_ru || !$folder_name_en || !$content_id){
			$errors.='Заполните все поля.<br />';
			$err++;
		}
		if($err==0){
			
			@mysql_query("UPDATE `cs_gallery_folder` SET
			`content_id`=".(int)$content_id.",
			`gallery_folder_name_az`=".$pdo->quote($folder_name_az).",
			`gallery_folder_name_ru`=".$pdo->quote($folder_name_ru).",
			`gallery_folder_name_en`=".$pdo->quote($folder_name_en).",
			`gallery_folder_ins_date`=".$pdo->quote($datetime)." WHERE `gallery_folder_id`=".(int)@$_GET['id']." LIMIT 1");
			
			?>
			<script>
				alert('Добавлено успешно.');
				window.location="index.php?page=list_gallery";
			</script>
			<?php
			
		}
	
	}

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактировать раздел</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	
		<?php
			if(isset($ok_folder) AND $err>0){
			?>
			<div class="error_div">
			<?php echo $errors; ?>
			</div>
			<?php
			}
		?>
		
		<form method="POST">
		
			<?php
			
				$sfll=mysql_query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
					while($rfll=mysql_fetch_assoc($sfll)){
					?>
					<p>
						<label>Название (<?php echo strtoupper($rfll['language_dir']); ?>)</label>
						<input value="<?php echo $rfgf['gallery_folder_name_'.$rfll['language_dir']]; ?>" class="text-input small-input" type="text" name="folder_name_<?php echo $rfll['language_dir']; ?>" />
					</p>
					<?php
					}
			
			?>
			
			<p>
				<label>Раздел</label>
				<select name="content_id" class="small-input">
					<option value="0"></option>
					<?php
					
						$sfop=mysql_query("SELECT `content_id`, `content_page_type`, `content_delete`, `content_hide_page`, `content_pagetitle_".DEFAULT_LANG_DIR."` FROM `cs_content_list` WHERE `content_page_type`=3 AND `content_delete`='no' AND `content_hide_page`='no' ORDER BY `content_id` ASC");
							while($rfop=mysql_fetch_assoc($sfop)){
							
								$sel_folder=($rfgf['content_id']==$rfop['content_id'])?'SELECTED="SELECTED"':'';
							
							?>
							<option <?php echo $sel_folder; ?> value="<?php echo $rfop['content_id']; ?>"><?php echo $rfop['content_pagetitle_'.DEFAULT_LANG_DIR]; ?></option>
							<?php
							}
							?>
				</select> 
			</p>

			<p>
				<input class="button" type="submit" name="ok_folder" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>