<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');
		
	$d_slide = $_GET['id'];
	//echo $d_slide;
	
	$delete_sql_str = "DELETE FROM `cs_slider` WHERE `id`=".$pdo->quote($d_slide)."";
	
	$delete_slider = $pdo->query($delete_sql_str);
	$delete_slider->execute();
?>