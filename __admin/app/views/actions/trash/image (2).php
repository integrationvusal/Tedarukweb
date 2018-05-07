<?php

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {die();}
header('Content-type: text/html; charset=utf-8');
define("_VALID_PHP", true);
require_once('../classes/Database.php');
$database = new Database;
$pdo = $database->Connect();

$action = $_POST['action'];

switch ($action) {

	case 'main_cat':
		$product_id = (int)$_POST['product_id'];
		$cat_id = (int)$_POST['cat_id'];
		$update_all = $pdo->exec('
                UPDATE `cs_products_cat` SET
                    `main_cat`= 0
                WHERE
                  `product_id`= '.$pdo->quote($product_id).'
            ');

		$update = $pdo->exec('
                UPDATE `cs_products_cat` SET
                    `main_cat`= 1
                WHERE
                  `product_id`= '.$pdo->quote($product_id).'
                  AND `cat_id`= '.$pdo->quote($cat_id).'
            ');

		if ($update) {
			echo 'ok';
		} else {
			echo 'not';
		}
		break;

    case 'main':
            $id = (int)$_POST['id'];
            $product_id = (int)$_POST['product_id'];
            $update = $pdo->exec('
                UPDATE `cs_products_image` SET
                    `type`= 0
                WHERE
                  `product_id`= '.$pdo->quote($product_id).'
            ');
            $update = $pdo->exec('
                UPDATE `cs_products_image` SET
                    `type`= 1
                WHERE
                  `id`= '.$pdo->quote($id).'
                  AND `product_id`= '.$pdo->quote($product_id).'
            ');
            if ($update) {
                echo 'ok';
            } else {
                echo 'not';
            }
        break;

    case 'delete':
        $id = (int)$_POST['id'];
        $product_id = (int)$_POST['product_id'];
        $name = $_POST['name'];
        $sql = "DELETE FROM `cs_products_image` WHERE `id` = :id AND `product_id` = :product_id ";
        $query = $pdo->prepare($sql);
        $result = $query->execute(array(
            ':id' => $id,
            ':product_id' => $product_id,
        ));

        if ($result) {
            unlink($_SERVER['DOCUMENT_ROOT'].'/amazonmanager/uploads/products/'.$name.'');
            echo 'ok';
        } else {
            echo 'not';
        }
        break;
}




