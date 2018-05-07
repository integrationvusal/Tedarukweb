<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

	$sfvl=$pdo->query("SELECT * FROM `cs_video_list` WHERE `video_id`=".(int)$_GET['id']." LIMIT 1");
		$rfvl=$sfvl->fetch(PDO::FETCH_ASSOC);

	$sfl=$pdo->query('SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC');
		while($rfl=$sfl->fetch(PDO::FETCH_ASSOC))	{

			$pt='video_name_'.$rfl['language_dir'];
			$$pt= @$_POST['video_name_'.$rfl['language_dir']];

		}
		
	$ok_video=@$_POST['ok_video'];
	$video_url=@$_POST['video_url'];
	$ftp_url=@$_POST['ftp_url'];
	$content_id=@$_POST['content_id'];
	$video_photo=@$_POST['video_photo'];
	$upload_photo=@$_POST['upload_photo'];
	$video_width=@$_POST['video_width'];
	$video_height=@$_POST['video_height'];
	
	$datetime=date('Y-m-d H:i:s');
	$err=0;
	
	global $errors;

	if(isset($ok_video)){
	
		if(!$video_name_az || !$video_name_ru || !$video_name_en || !$content_id){
			$errors.='Заполните все поля.<br />';
			$err++;
		}
		if($err==0){
			
			if(is_file($_FILES["video_photo"]["tmp_name"])){
			
				$file=@$_FILES["video_photo"]["tmp_name"];
				$fot='video_'.date('Ymdhis').'.png';
				$upload_photo="`video_photo`=".$pdo->quote($fot).",";
				
				@mkdir($_SERVER['DOCUMENT_ROOT']."/uploads/video_img/", 0777, true);
				@resize($file, $_SERVER['DOCUMENT_ROOT']."/uploads/video_img/".$fot, 300,false);
				
			}
			if(!is_file($_FILES["video_photo"]["tmp_name"])){
				$upload_photo='';
			}
			
			$pdo->query("UPDATE `cs_video_list` SET
			`content_id`=".(int)$content_id.",
			`video_name_az`=".$pdo->quote(htmlspecialchars($video_name_az)).",
			`video_name_ru`=".$pdo->quote(htmlspecialchars($video_name_ru)).",
			`video_name_en`=".$pdo->quote(htmlspecialchars($video_name_en)).",
			`video_url`=".$pdo->quote($video_url).",
			`ftp_url`=".$pdo->quote($ftp_url).",
			`video_width`=".$pdo->quote($video_width).",
			`video_height`=".$pdo->quote($video_height).",
			".$upload_photo."
			`video_ins_date`=".$pdo->quote($datetime)." WHERE `video_id`=".(int)$_GET['id']." LIMIT 1");
			
			?>
			<script>
				alert('Сохранено успешно.');
				window.location="index.php?page=list_video";
			</script>
			<?php
			
		}
	
	}

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактировать видео</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	
		<?php
			if(isset($ok_video) AND $err>0){
			?>
			<div class="error_div">
			<?php echo $errors; ?>
			</div>
			<?php
			}
		?>
		
		<form method="POST"  enctype="multipart/form-data">
		
			<?php
			
				$sfll=$pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
					while($rfll=$sfll->fetch(PDO::FETCH_ASSOC)){
					?>
					<p>
						<label>Название (<?php echo strtoupper($rfll['language_dir']); ?>)</label>
						<input value="<?php echo $rfvl['video_name_'.$rfll['language_dir']]; ?>" class="text-input small-input" type="text" name="video_name_<?php echo $rfll['language_dir']; ?>" />
					</p>
					<?php
					}
			
			?>
			
			<p>
				<label>URL YouTube (ID видео)</label>
				<input value="<?php echo $rfvl['video_url']; ?>" class="text-input small-input" type="text" name="video_url" />
			</p>
			
			<p>
				<label>URL FTP (uploads/video/ {имя видео} .flv )</label>
				<input value="<?php echo $rfvl['ftp_url']; ?>" class="text-input small-input" type="text" name="ftp_url" />
			</p>
			
			<p>
				<label>Ширина (URL FTP)</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfvl['video_width']; ?>"  name="video_width" />
			</p>
			
			<p>
				<label>Высота (URL FTP)</label>
				<input class="text-input small-input" type="text" value="<?php echo $rfvl['video_height']; ?>"  name="video_height" />
			</p>
			
			<p>
				<label>Раздел</label>
				<select name="content_id" class="small-input">
					<option value="0"></option>
					<?php
					
						$sfop=$pdo->query("SELECT `content_id`, `content_page_type`, `content_delete`, `content_hide_page`, `content_pagetitle_".DEFAULT_LANG_DIR."` FROM `cs_content_list` WHERE `content_page_type`=4 AND `content_delete`='no' AND `content_hide_page`='no' ORDER BY `content_id` ASC");
							while($rfop=$sfop->fetch(PDO::FETCH_ASSOC)){
							
								$select_video=($rfvl['content_id']==$rfop['content_id'])?'SELECTED="SELECTED"':'';
							
							?>
							<option <?php echo $select_video; ?> value="<?php echo $rfop['content_id']; ?>"><?php echo $rfop['content_pagetitle_'.DEFAULT_LANG_DIR]; ?></option>
							<?php
							}
							?>
				</select> 
			</p>
			
			<p>
				<label>Фотография</label>
				<input type="file" name="video_photo" />
			</p>

			<p>
				<input class="button" type="submit" name="ok_video" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>