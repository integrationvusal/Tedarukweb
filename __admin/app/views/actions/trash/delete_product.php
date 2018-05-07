<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

		
		 $deleted_id = $_GET['id'];
		
	try{
		
		//$delrows=$pdo->exec("DELETE FROM `cs_product` WHERE id=$deleted_id");
		$delrows = $pdo->exec("UPDATE `cs_product` SET `delete` = 1 WHERE `id` = ".$pdo->quote($deleted_id)."");


		echo "<script language='javascript'>
		        window.location = 'index.php?page=view_product';
		      </script>";
		}
		catch(PDOException $e){
		echo 'Error : '.$e->getMessage();
		exit();
	}
?>
