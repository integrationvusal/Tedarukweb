<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');


  $id = (int)$_GET['id'];
  $delrows = $pdo->exec(" DELETE FROM `cs_orders_status` WHERE `id` = ".$id." ");

    if($delrows)
    {
        echo "<script language='javascript' type='text/javascript'>alert('Delete success')</script>";
        echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=list_orders_status'</script>";
    }
    else
    {
        echo "<script language='javascript' type='text/javascript'>alert('Delete problem')</script>";
    }

?>