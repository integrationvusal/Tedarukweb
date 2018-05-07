<?php
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {die();}
header('Content-type: text/html; charset=utf-8');
define("_VALID_PHP", true);
require_once('../classes/Database.php');
$database = new Database;
$pdo = $database->Connect();

// Getting some additional info about user (for instance name, lastname, mob, addr and e.t.c)

if( isset($_POST['action']) && isset($_POST['id']) && isset($_POST['val']) )
{
    $actions = array('day','sale');
    $id = intval($_POST['id']);
    $val = intval($_POST['val']);
    
    if(in_array($_POST['action'],$actions)) {
        if($_POST['action'] == 'day') {
            $query = $pdo->query("UPDATE `cs_product` SET `product_day` = ".intval($val)." WHERE `id`= ".intval($id)." LIMIT 1")->execute();
            if($query) {
                echo true;
            }
        } elseif($_POST['action'] == 'sale') {
            $query = $pdo->query("UPDATE `cs_product` SET `sale` = ".intval($val)." WHERE `id`= ".intval($id)." LIMIT 1")->execute();
            if($query) {
                echo true;
            }
        }
    } else {
        die();
    }
}

?>

