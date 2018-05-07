<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');
		
	if(isset($_POST['send'])){
		 
	 $error_count = 0;
	 if(isset($_POST['glob_cat_az']) && $_POST['glob_cat_az'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['glob_cat_en']) && $_POST['glob_cat_en'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['glob_cat_ru']) && $_POST['glob_cat_ru'] == ''){
	  $error_count++;
	 }
	 
	 
	 if($error_count == 0){
	 
	 $datetime=date('Y-m-d H:i:s');
	
	 try {
		$stmt = $pdo->prepare("INSERT INTO `cs_category` (parent_id,title_az,title_ru,title_en,added_date) VALUES (?,?,?,?,?)");
		$stmt -> execute(array(0,$_POST['glob_cat_az'],$_POST['glob_cat_ru'],$_POST['glob_cat_en'],$datetime));
		echo "<script language='javascript'>
		        alert('Добавлено успешно.');
				window.location='index.php?page=view_global_cat';
		      </script>";
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
		<h3>Добавить глобальную категорию</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
		
		<?php
		 if($error_count > 0){
		  echo  '<div class="fill_all_fields">Заполните все поля</div>';
		 }
		?>
        <div>
		
		 <form name="add_glob_cat" method="POST">
		  <label>* Название категории (AZ)</label>
		  <input type="text" name="glob_cat_az" class="text-input small-input" /> <br /> <br />
		  <label>* Название категории (RU)</label>
		  <input type="text" name="glob_cat_ru" class="text-input small-input" /> <br /> <br />
		  <label>* Название категории (EN)</label>
		  <input type="text" name="glob_cat_en" class="text-input small-input" /> <br /> <br />
		  <input type="submit" name="send" value="Добавить" class="button" />
		 </form>
		</div>		
			
			
		<div class="clear"></div>
        
	</div>
    
</div>
