<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

 $id = (int)$_GET['id'];
 $res = $pdo->query("DELETE FROM `cs_orders` WHERE `id` = ".$id."")->execute();

if( $res )
{
     echo "<script type='text/javascript' language='javascript'>window.location = 'index.php?page=new_orders'</script>";
}
?>