<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');	
	
	
	
	if(isset($_POST['save'])){
		 
	 $error_count = 0;
	 if(isset($_POST['title_az']) && $_POST['title_az'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['title_en']) && $_POST['title_en'] == ''){
	  $error_count++;
	 }
	 if(isset($_POST['title_ru']) && $_POST['title_ru'] == ''){
	  $error_count++;
	 }
	 
	 
	 if($error_count == 0){
	 
	 $datetime=date('Y-m-d H:i:s');
	
	 try {
		$stmt = $pdo->prepare("INSERT INTO `cs_category` (parent_id,title_az,title_ru,title_en,added_date) VALUES (?,?,?,?,?)");
		$stmt -> execute(array($_POST['parent_id'],$_POST['title_az'],$_POST['title_ru'],$_POST['title_en'],$datetime));
		echo "<script language='javascript'>
		         alert('Добавлено успешно.');
				 window.location='index.php?page=view_cat';
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
		<h3>Добавить категорию</h3>
		<div class="clear"></div>
	</div>
	
	
	
	<div class="content-box-content">
       <form name="add_cat" action="" method="POST"> 
	     <label>* Название категории (AZ)</label>
         <input type="text" name="title_az" class="text-input small-input" /><br /><br />
		 <label>* Название категории (EN)</label>
         <input type="text" name="title_en" class="text-input small-input" /><br /><br />
		 <label>* Название категории (RU)</label>
         <input type="text" name="title_ru" class="text-input small-input" /><br /><br />
		 
		 <label>* Добавить в глобальную категорию</label>
		 <select name="parent_id">
		    
				<?php
						  $row1 = $pdo->query("SELECT * FROM `cs_category` WHERE `parent_id`=0"); 
						  while($res1 = $row1->fetch(PDO::FETCH_ASSOC)){
						    printf("<option value='%s'>%s</option>",$res1['id'],$res1['title_ru']);
						  };
						 
				?>
		 </select> <br /></br />
		 
		 <input type="submit" name="save" value="Сохранить" class="button" />
	   </form>	
	<div class="clear"></div>
	</div>
</div>
