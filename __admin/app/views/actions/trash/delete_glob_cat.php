<?php

	if(!defined("_VALID_PHP"))
		die('Direct access to this location is not allowed.');

		
		 $deleted_id = $_GET['id'];
		
	try{
		
		$delrows=$pdo->exec("DELETE FROM `cs_category` WHERE id=".$pdo->quote($deleted_id)."");
		echo "<script language='javascript'>
		        alert('Redirecting you to glog. category list');
		      </script>";
		}
		catch(PDOException $e){
		echo 'Error : '.$e->getMessage();
		exit();
	}
?>
