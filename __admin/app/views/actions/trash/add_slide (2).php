<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

	
	$ok_slide = $_POST['ok_slide'];
	
	$slide_title_az = htmlspecialchars($_POST['slide_title_az']);
	$slide_title_en = htmlspecialchars($_POST['slide_title_en']);
	$slide_title_ru = htmlspecialchars($_POST['slide_title_ru']);
	
	$slide_text_az = htmlspecialchars($_POST['slide_text_az']);
	$slide_text_en = htmlspecialchars($_POST['slide_text_en']);
	$slide_text_ru = htmlspecialchars($_POST['slide_text_ru']);	
	
	$page_url = htmlspecialchars($_POST['page_url']);	
	
	$link_title_az = htmlspecialchars($_POST['link_title_az']);
	$link_title_en = htmlspecialchars($_POST['link_title_en']);
	$link_title_ru = htmlspecialchars($_POST['link_title_ru']);
	
	$slide_order = $_POST['slide_order'];
	
	$has_error = false;
	
	if(isset($ok_slide)){ 
	    if(!$slide_title_az || !$slide_title_en || !$slide_title_ru || !$slide_text_az || !$slide_text_en || !$slide_text_ru || !$page_url || !$link_title_az || !$link_title_en || !$link_title_ru){
		   echo "<script>alert('required fieldt must not be blank');</script>";
		   
		   $has_error = true;
		}else{
		   // OK
		 $prefix =  date("Y-m-d-h-m-s");  
		   
		 if(is_file($_FILES["slide_big_photo"]["tmp_name"])){
		   		 
		$big_image_new_name = 'big_'.$prefix.$_FILES["slide_big_photo"]["name"];	 
		
		$upload_success = move_uploaded_file( $_FILES["slide_big_photo"]["tmp_name"] , $_SERVER['DOCUMENT_ROOT']."/uploads/slider_image/big/".$big_image_new_name); 
			 
			
			 
		 }; 



         
		  if(is_file($_FILES["slide_small_photo"]["tmp_name"])){
		   		 
		$small_image_new_name = 'small_'.$prefix.$_FILES["slide_small_photo"]["name"];	 
		
		$upload_success = move_uploaded_file( $_FILES["slide_small_photo"]["tmp_name"] , $_SERVER['DOCUMENT_ROOT']."/uploads/slider_image/small/".$small_image_new_name); 
			 
			
			 
		 }; 



		$todays_date = date("Y-m-d");
         		 
		  
		   
		 $STH = $pdo->prepare("INSERT INTO `cs_slider`( `title_az`,`text_az`,`link_url`,`link_title_az`,`order`,`enable`,`title_en`,`title_ru`,`text_en`,`text_ru`,`link_title_ru`,`link_title_en`,`big_photo`,`small_photo`,`added_date` )   VALUES ( ".$pdo->quote($slide_title_az).",".$pdo->quote($slide_text_az).",".$pdo->quote($page_url).",".$pdo->quote($link_title_az).",".$pdo->quote($slide_order).",'1',".$pdo->quote($slide_title_en).",".$pdo->quote($link_title_ru).",".$pdo->quote($slide_text_en).",".$pdo->quote($slide_text_ru).",".$pdo->quote($link_title_ru).",".$pdo->quote($link_title_en).",".$pdo->quote($big_image_new_name).",".$pdo->quote($small_image_new_name).",".$pdo->quote($todays_date).")");
          $STH->execute();
		   
		   echo "<script>
				alert('Добавлено успешно.');
				window.location=\"index.php?page=list_slide\";
			</script>";
			
		}
	}
	
	
	

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Добавить слайд</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	
		
		
		<?php
		if($has_error){echo '<div class="error_div">Заполнены не все поля</div>';};
		?>
		<form method="POST"  enctype="multipart/form-data">
		
			<?php
			
				$sfll=$pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
					while($rfll=$sfll->fetch(PDO::FETCH_ASSOC)){
					?>
					<p>
						<label>* Название слайда (<?php echo strtoupper($rfll['language_dir']); ?>)</label>
						<input class="text-input small-input" type="text" name="slide_title_<?php echo $rfll['language_dir']; ?>" />
					</p>
					<p>
						<label>* Текст слайда (<?php echo strtoupper($rfll['language_dir']); ?>)</label>
						<textarea name="slide_text_<?php echo $rfll['language_dir']; ?>" class="textarea_for_slider_text"></textarea>
					</p>
					<?php
					}
			
			?>
			
			<p>
				<label>URL ( link for 'Read more' )</label>
				<input class="text-input small-input" type="text" name="page_url" />
			</p>
			
			
			
			
			
			<?php
			
				$sfll=$pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
					while($rfll=$sfll->fetch(PDO::FETCH_ASSOC)){
					?>
					<p>
						<label>URL title ( title for 'Read more' ) (<?php echo strtoupper($rfll['language_dir']); ?>)</label>
						<input class="text-input small-input" type="text" name="link_title_<?php echo $rfll['language_dir']; ?>" />
					</p>
					
					<?php
					}
			
			?>
			
			<!--<p>
				<label>URL FTP (uploads/video/ {имя видео} .flv )</label>
				<input class="text-input small-input" type="text" name="ftp_url" />
			</p>-->
			
			<!--<p>
				<label>Ширина (URL FTP)</label>
				<input class="text-input small-input" type="text" name="video_width" />
			</p>-->
			
			<!--<p>
				<label>Высота (URL FTP)</label>
				<input class="text-input small-input" type="text" name="video_height" />
			</p>-->
			
			<!--<p>
				<label>* Раздел</label>
				<select name="content_id" class="small-input">
					<option value="0"></option>
					<?php
					
						$sfop=$pdo->query("SELECT `content_id`, `content_page_type`, `content_delete`, `content_hide_page`, `content_pagetitle_".DEFAULT_LANG_DIR."` FROM `cs_content_list` WHERE `content_page_type`=4 AND `content_delete`='no' AND `content_hide_page`='no' ORDER BY `content_id` ASC");
							while($rfop=$sfop->fetch(PDO::FETCH_ASSOC)){
							?>
							<option value="<?php echo $rfop['content_id']; ?>"><?php echo $rfop['content_pagetitle_'.DEFAULT_LANG_DIR]; ?></option>
							<?php
							}
							?>
				</select> 
			</p>-->
			
			<p>
				<label>Фотография (Большая)</label>
				<input type="file" name="slide_big_photo" />
			</p>
			<p>
				<label>Фотография (маленькая)</label>
				<input type="file" name="slide_small_photo" />
			</p>
			
			<p>
				<label>Очередь</label>
				<input class="text-input small-input" type="text" name="slide_order" />
			</p>

			<p>
				<input class="button" type="submit" name="ok_slide" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>