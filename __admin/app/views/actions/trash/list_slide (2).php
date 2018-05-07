<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

?>
<div class="content-box">	
	<div class="content-box-header">
		<h3>Список слайдов</h3>
		<div class="clear"></div>
	</div>
	
	<table cellspacing=0 cellpadding=0>
	   <tr>
	      <th><b>ID</b></th>
	      <th><b>Название</b></th>
	      <th><b>Доступно</b></th>
	      <th><b>Дата добавления</b></th>
	      <th><b>Управление</b></th>
	   </tr>
	   <?php
	    
         $sql=$pdo->query(" SELECT * FROM `cs_slider`");
		 
		 while($row=$sql->fetch(PDO::FETCH_ASSOC)){
		    
			$control_panel = "<a href='index.php?page=edit_slider&id=".$row['id']."'><img src = 'templates/default/images/icons/pencil.png' /></a> &nbsp; 
			                  <a href='index.php?page=delete_slider&id=".$row['id']."'><img src = 'templates/default/images/icons/cross.png' /></a>
			";	
			
			if($row['enable'] == 0){$row['enable'] = 'Отключено';};
			if($row['enable'] == 1){$row['enable'] = 'Доступно';};
			
			
		    echo '<tr>';
			 printf( "<td>%s</td>   <td>%s</td>   <td>%s</td> <td>%s</td> <td>%s</td>" , $row['id'],$row['title_ru'],$row['enable'],$row['added_date'] ,$control_panel );
		    echo '</tr>';
		 }
	   ?>
	</table>
	
	
	<div class="content-box-content">
	</div>
</div>