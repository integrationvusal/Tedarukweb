<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

    $error_count = 0;
	if(isset($_POST['save'])){

	 if(isset($_POST['name_az']) && $_POST['name_az'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['name_ru']) && $_POST['name_ru'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['name_en']) && $_POST['name_en'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['color_print_speed']) && $_POST['color_print_speed'] == ''){
	  $error_count++;
	 }
	 
	 
	 if($error_count == 0){
	 
	 $datetime=date('Y-m-d H:i:s');
	
	 try {	 
	 
		$stmt = $pdo->prepare("INSERT INTO `cs_product` 
		                                       (name_az,
											   name_ru,
											   name_en,
											   color_print_speed,
											   mono_print_speed,
											   print_resolution,
											   page_describe_language,
											   difference_az,
											   difference_en,
											   difference_ru,
											   model,
											   description_az,
											   description_ru,
											   description_en,
											   full_text_az,
											   full_text_ru,
											   full_text_en,
											   enable,
											   cat_id,
											   added_date) 
							VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
		$stmt -> execute(array(
		                                       $_POST['name_az'],
											   $_POST['name_ru'],
											   $_POST['name_en'],
											   $_POST['color_print_speed'],
											   $_POST['mono_print_speed'],
											   $_POST['print_resolution'],
											   $_POST['page_description_language'],
											   $_POST['difference_az'],
											   $_POST['difference_en'],
											   $_POST['difference_ru'],
											   $_POST['model'],
											   $_POST['description_az'],
											   $_POST['description_ru'],
											   $_POST['description_en'],
											   $_POST['full_text_az'],
											   $_POST['full_text_ru'],
											   $_POST['full_text_en'],
											   1,
											   $_POST['cat_id'],
											   $datetime));
		/*echo "<script language='javascript'>
		        alert('Добавлено успешно.');
				window.location='index.php?page=view_product';
		      </script>";
		*/
		}
		catch(PDOException $e){
		echo 'Error : '.$e->getMessage();
		exit();
       }// try end
	 } // if have not any errors
	}
	
	
?>


	<div class="content-box">	
	<div class="content-box-header">
		<h3>Добавить продукт</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
		
		<?php
		 if($error_count > 0){
		  echo  '<div class="fill_all_fields">Заполните все поля</div>';
		 }
		?>
		
        <form name="add_product" action="" method="POST" enctype="multipart/form-data">
		  
			  <!-------НАЗВАНИЕ ПРОДУКТОВ-------->
			  <label>* Название продукта (AZ)</label>
			  <input type="text" name="name_az" class="text-input small-input"/> <br /> <br />
			  <label>* Название продукта (RU)</label>
			  <input type="text" name="name_ru" class="text-input small-input" /> <br /> <br />
			  <label>* Название продукта (EN)</label>
			  <input type="text" name="name_en" class="text-input small-input" /> <br /> <br />
			  
			  <label>* Скорость печати цвет </label>
			  <input type="text" name="color_print_speed" class="text-input small-input" /> <br /> <br />
			  <label>* Скорость печати моно </label>
			  <input type="text" name="mono_print_speed" class="text-input small-input" /> <br /> <br />
			  <label>* Разрешение печати </label>
			  <input type="text" name="print_resolution" class="text-input small-input" /> <br /> <br />
			  
			  <label>* Язык описания страниц </label>
			  <input type="text" name="page_description_language" class="text-input small-input" /> <br /> <br />
			  
			  <!---ОТЛИЧИТЕЛЬНЫЕ ЧЕРТЫ--->
			  <label>* Отличительные особенности (AZ)</label>
			  <textarea  class="text_area" type="text" name="difference_az" class="text-input small-input" ></textarea> <br /> <br />
			  <label>* Отличительные особенности (EN)</label>
			  <textarea  class="text_area" type="text" name="difference_en" class="text-input small-input" ></textarea> <br /> <br />
			  <label>* Отличительные особенности (RU)</label>
			  <textarea  class="text_area" type="text" name="difference_ru" class="text-input small-input" > </textarea> <br /> <br />
			  
			  <label>* Модель</label>
			  <input type="text" name="model" class="text-input small-input" /> <br /> <br />
			  
			  
			  <!----НЕБОЛЬШОЕ ОПИСАНИЕ---->
			  <label>* Описание (AZ)</label>
			  <textarea  class="text_area" type="text" name="description_az" class="text-input small-input" ></textarea> <br /> <br />
			  <label>* Описание (RU)</label>
			  <textarea class="text_area"  type="text" name="description_ru" class="text-input small-input" ></textarea> <br /> <br />
			  <label>* Описание (EN)</label>
			  <textarea class="text_area" type="text" name="description_en" class="text-input small-input" ></textarea> <br /> <br />
			  
			  <!----ПОЛНЫЙ ТЕКСТ---->
			  <label>* Полное описание (AZ)</label>
			  <textarea  class="text_area" name="full_text_az" class="text-input small-input" > </textarea> <br /> <br />
			  <label>* Полное описание (EN)</label>
			  <textarea type="text" class="text_area" name="full_text_en" class="text-input small-input" > </textarea> <br /> <br />
			  <label>* Полное описание (RU)</label>
			  <textarea type="text" class="text_area" name="full_text_ru" class="text-input small-input" ></textarea> <br /> <br />
			  
			  
			  			  
			  <!--------ФОТОГРАФИИ ТОВАРОВ------->
			  <label>* Большое фото</label>
			  <input type="file" name="img_big" class="text-input small-input" /> <br /> <br />
			  <label>* Маленькое фото</label>
			  <input type="file" name="img_small"  class="text-input small-input"/> <br /> <br />
 		      
			  <!--------Категория------->
			  <?php
			   $res3 = $pdo->query("
			        SELECT *
			        FROM `cs_content_list`
			        WHERE
			            `content_delete`= 'no' AND
			            `content_page_type`= 'category'
			   ");
			  ?>
			  <label>* Добавить в категорию</label>
			  <select name="cat_id">
			    <?php
				  while($row3 = $res3->fetch(PDO::FETCH_ASSOC)){
				    printf("
					  <option value='%s'>%s</option>
					",$row3['content_id'],$row3['content_pagetitle_ru']);
				  }
				?>
			  </select>
			  <br /> <br />
			  
		      <input type="submit" name="save" class="button" value="Сохранить" />
		</form>
			
		<div class="clear"></div>
        
	</div>
    
</div>
