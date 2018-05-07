<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

		
	if(isset($_POST['save'])){
		 
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
	 
	
	
	 try { 
	        $a = $_POST['glob_cat_az'];
	        $b = $_POST['glob_cat_ru'];
	        $c = $_POST['glob_cat_en'];
	        $id = $_POST['id'];
			
			$count = $pdo->exec("UPDATE `cs_category` SET `title_az`= ".$pdo->quote($a)." , `title_ru`= ".$pdo->quote($b)." ,`title_en`= ".$pdo->quote($c)."   WHERE `id`=".$pdo->quote($id)."");
		
		
		echo "<script language='javascript'>
		        alert('Redirecting you to global category list');
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
		<h3>Редактирование глобальных категорий</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	   
		<?php
		 if($error_count > 0){
		  echo  '<div class="fill_all_fields">Заполните все поля</div>';
		 }
		?>
		
		
		<?php
		$edited_id = $_GET['id'];
	
		  $row = $pdo->query("SELECT * FROM `cs_category` WHERE `id`=".$pdo->quote($edited_id)."");
		  $res = $row->fetch(PDO::FETCH_ASSOC);
		  
		?>
		
	<form name="edit_glob_cat" action="" method="POST">	
	    <label>* Название категории (AZ)</label>
		<input type="text" name="glob_cat_az" value="<?php echo $res['title_az']; ?>" class="text-input small-input" /><br /><br />
		<label>* Название категории (EN)</label>
		<input type="text" name="glob_cat_en" value="<?php echo $res['title_en']; ?>" class="text-input small-input" /><br /><br />
		<label>* Название категории (RU)</label>
		<input type="text" name="glob_cat_ru" value="<?php echo $res['title_ru']; ?>" class="text-input small-input" /><br /><br />
		<input type="hidden" name="id" value="<?php echo $res['id']; ?>" />
		
		
		<input type="submit" name="save" class="button" value="Сохранить" />
	</form>   
		<div class="clear"></div>
   
	</div>
    
</div>