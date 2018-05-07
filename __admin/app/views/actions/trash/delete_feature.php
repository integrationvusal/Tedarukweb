<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

		if(isset($_GET['id']))
		{
		 $deleted_id = $_GET['id'];
		}
		else{
		 die('No parameters received');
		}
		
	try{
		
		$delrows=$pdo->exec("DELETE FROM `cs_publish` WHERE `id`=".$pdo->quote($deleted_id)."");
		echo "<script language='javascript'>
		        alert('Redirecting you to product list');
		      </script>";
		}
		catch(PDOException $e){
		echo 'Error : '.$e->getMessage();
		exit();
	}
?>
