<?php

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {die();}
header('Content-type: text/html; charset=utf-8');
define("_VALID_PHP", true);
require_once('../classes/Database.php');
$database = new Database;
$pdo = $database->Connect();

$file_input = $_POST['image'];
$name = end(explode('/', $_POST['image'])).'_'.time();
$product_id = (int)$_POST['product_id'];
$file_output = $_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products/'.$name.'';
$crop_save = crop($file_input, $file_output, array(0, 0, 500, 351), $percent = false);

if ($crop_save) {
    $sql = "INSERT INTO `cs_products_image` (`product_id`, `name`, `type`) VALUES (:product_id, :name, :type)";
    $query = $pdo->prepare($sql);
    $result = $query->execute(array(
        ':product_id' => $product_id,
        ':name' => $name,
        ':type' => 0,
    ));
    if ($result) {
        echo 'ok';
    } else {
        echo 'not';
    }
} else {
    echo 'not';
}




