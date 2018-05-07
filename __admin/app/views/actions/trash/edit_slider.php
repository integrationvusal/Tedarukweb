<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');	
      
	  $slide_id = htmlspecialchars($_GET['id']); 	  
	  $sds = $pdo->query("SELECT * FROM `cs_slider` WHERE `id`=".$pdo->quote($slide_id)."");
	  $ert = $sds->fetch(PDO::FETCH_ASSOC);


      $has_error = false;
	  if(isset($_POST['ok_slide'])){
	     // button pressed, i would rather check required fields
		
		
		$sl_title_az = $_POST['slider_title_az'];
		$sl_title_en = $_POST['slider_title_en'];
		$sl_title_ru = $_POST['slider_title_ru'];
		
		
		$sl_text_az = $_POST['slide_text_az'];
		$sl_text_en = $_POST['slide_text_en'];
		$sl_text_ru = $_POST['slide_text_ru'];
		
		$sl_page_url = $_POST['page_url'];
		
		$sl_link_titile_az = $_POST['link_title_az'];
		$sl_link_titile_en = $_POST['link_title_en'];
		$sl_link_titile_ru = $_POST['link_title_ru'];
		
		$sl_big_photo = $_POST['slide_big_photo'];
		$sl_small_photo = $_POST['slide_small_photo'];
		
		$sl_order = $_POST['slide_order'];
		$sl_activity = $_POST['activity'];
		

		 if(!$sl_title_az || !$sl_title_en || !$sl_title_ru || !$sl_text_az || !$sl_text_en || !$sl_text_ru || !$sl_page_url || !$sl_link_titile_az || !$sl_link_titile_ru || !$sl_link_titile_en || !$sl_big_photo || !$sl_small_photo || !$sl_order){
		     echo '<script>alert("required fields must not be blank"); </script>';
			 $has_error = true;	
		
		 }else{
		  
		  $sl_query_update = $pdo->prepare("UPDATE `cs_slider` 
		  
				  SET  
				  
				  `title_az` =        ".$pdo->quote($sl_title_az).",
				  `big_photo` =       ".$pdo->quote($sl_big_photo).",
				  `small_photo`=      ".$pdo->quote($sl_small_photo).",
				  `text_az`=          ".$pdo->quote($sl_text_az).",
				  `link_url`=         ".$pdo->quote($sl_page_url).",
				  `link_title_az`=    ".$pdo->quote($sl_link_titile_az).",
				  `order`=            ".$pdo->quote($sl_order).",
				  `enable`=           ".$pdo->quote($sl_activity).",
				  `title_en`=         ".$pdo->quote($sl_title_en).",
				  `title_ru`=         ".$pdo->quote($sl_title_ru).",
				  `text_en`=          ".$pdo->quote($sl_text_en).",
				  `text_ru`=          ".$pdo->quote($sl_text_ru).",
				  `link_title_ru`=    ".$pdo->quote($sl_link_titile_ru).",
				  `link_title_en`=    ".$pdo->quote($sl_link_titile_en)."
				  
				  where `id`=".$pdo->quote($slide_id)." ");
				  
		  $sl_query_update->execute();
			  echo '<script>alert("Успешна изменена"); window.location="index.php?page=list_slide"; </script>';
		 }
	  }
	
?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Редактировать слайд</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	
		<?php
		   if($has_error){echo '<div class="error_div">Не все поля заполнены</div>';}
		?>
		<form method="POST"  enctype="multipart/form-data">
		            <!--AZ-->
					<p>
						<label>* Название слайда (AZ)</label>
						<input class="text-input small-input" type="text" value="<?php echo $ert['title_az']; ?>" name="slider_title_az" />
					</p>
					<p>
						<label>* Текст слайда (AZ)</label>
						<textarea name="slide_text_az" class="textarea_for_slider_text"><?php echo $ert['text_az']; ?></textarea>
					</p>
					<!--RU-->
					<p>
						<label>* Название слайда (RU)</label>
						<input class="text-input small-input" type="text" value="<?php echo $ert['title_ru']; ?>" name="slider_title_ru" />
					</p>
					<p>
						<label>* Текст слайда (RU)</label>
						<textarea name="slide_text_ru" class="textarea_for_slider_text"><?php echo $ert['text_ru']; ?></textarea>
					</p>
					<!--ENG-->
					<p>
						<label>* Название слайда (EN)</label>
						<input class="text-input small-input" type="text" value="<?php echo $ert['title_en']; ?>" name="slider_title_en" />
					</p>
					<p>
						<label>* Текст слайда (EN)</label>
						<textarea name="slide_text_en" class="textarea_for_slider_text"><?php echo $ert['text_en']; ?></textarea>
					</p>
					
			
			<p>
				<label>* URL ( link for 'Read more' )</label>
				<input class="text-input small-input" type="text" value="<?php echo $ert['link_url']; ?>" name="page_url" />
			</p>
			
			
			
			
			
			
			
				
					<p>
						<label>* URL title ( title for 'Read more' ) (AZ)</label>
						<input class="text-input small-input"  value="<?php echo $ert['link_title_az']; ?>"  type="text" name="link_title_az" />
					</p>
				
					<p>
						<label>* URL title ( title for 'Read more' ) (EN)</label>
						<input class="text-input small-input" value="<?php echo $ert['link_title_en']; ?>" type="text" name="link_title_en" />
					</p>
				
					<p>
						<label>* URL title ( title for 'Read more' ) (RU)</label>
						<input class="text-input small-input" value="<?php echo $ert['link_title_ru']; ?>" type="text" name="link_title_ru" />
					</p>
					
				
			
			
			<p>
				<label>* Фотография (Большая)</label>
				<input type="text"  class="text-input small-input" value="<?php echo $ert['big_photo']; ?>"  name="slide_big_photo" style="width:300px !important;" />
			</p>
			<p>
				<label>* Фотография (маленькая)</label>
				<input type="text"  class="text-input small-input"  value="<?php echo $ert['small_photo']; ?>" name="slide_small_photo" style="width:300px !important;"/>
			</p>
			
			<p>
				<label>* Очередь</label>
				<input class="text-input small-input" value="<?php echo $ert['order']; ?>" type="text" name="slide_order" />
			</p>
			
			
			
			<p>
			 <fieldset >
			  <legend>Доступность</legend>
			    <label><input type="radio" name="activity" value="1" <?php if($ert['enable'] == 1){echo 'checked';}?> >&nbsp;Доступно</label>
				<label><input type="radio" name="activity" value="0" <?php if($ert['enable'] == 0){echo 'checked';}?> >&nbsp;Отключить</label>
			 </fieldset>
			</p>

			<p>
				<input class="button" type="submit" name="ok_slide" value="Сохранить" />
			</p>
		
		</form>
			
		<div class="clear"></div>
	</div>
</div>