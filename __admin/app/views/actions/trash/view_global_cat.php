<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Просмотр глобальных категорий</h3>
		<div class="clear"></div>
	</div>
	<div class="content-box-content">
	
	<table>
	  <tr>
	    <th>ID</th>
	    <th>Название</th>
	    <th>Дата добавления</th>
	    <th>Управление</th>
	  </tr>
	  
	 <?php
	  $row = $pdo->query('SELECT * FROM `cs_category` WHERE `parent_id` = 0');
	  
	  while($res = $row->fetch(PDO::FETCH_ASSOC)){
	  
	  
	  $control_panel = "<a href='index.php?page=edit_glob_cat&id=".$res['id']."'><img src = 'templates/default/images/icons/pencil.png' /></a> &nbsp; 
			                  <a href='index.php?page=delete_glob_cat&id=".$res['id']."'><img src = 'templates/default/images/icons/cross.png' /></a>
			";
	  
	  
	  
	  
	   printf("<tr>
	            <td>%s</td>
	            <td>%s</td>
	            <td>%s</td>
	            <td>%s</td>
	          </tr>",$res['id'],$res['title_ru'],$res['added_date'],$control_panel);
	  }
	  
	  
	 ?>
	  
	</table>
		
		<div class="clear"></div>
        
	</div>
    
</div>