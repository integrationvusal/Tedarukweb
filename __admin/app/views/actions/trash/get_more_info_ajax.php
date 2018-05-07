<?php
if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {die();}
header('Content-type: text/html; charset=utf-8');
define("_VALID_PHP", true);
require_once('../classes/Database.php');
$database = new Database;
$pdo = $database->Connect();

// Getting some additional info about user (for instance name, lastname, mob, addr and e.t.c)

if( isset($_POST['id']) )
{
    $id = $_POST['id'];
    $query = $pdo->query("SELECT * FROM `cs_site_users` WHERE `id`=".$pdo->quote($id)."")->fetch(PDO::FETCH_ASSOC);

    echo "<p><span class='close_ajax'>x</span></p>";
    echo '<p><b>Имя</b> : '.$query['name'].'</p>';
    echo '<p><b>Фамилия</b> : '.$query['surname'].'</p>';
    echo '<p><b>email</b> : '.$query['email'].'</p>';
    echo '<p><b>Телефон</b> : '.$query['phone'].'</p>';
    echo '<p><b>Мобильный</b> : '.$query['mob'].'</p>';
    echo '<p><b>Адрес</b> : '.$query['address'].'</p>';
    echo '<p><b>Дата регистрации</b> : '.$query['reg_date'].'</p>';
    echo "<script type='text/javascript' language='javascript'>
    var close_icon = $('.close_ajax');
    close_icon.click(function(){
      $(this).parent().parent().css('display','none');
    }); // click end
   </script>";

}


// Lock & unlock user


if( isset($_POST['user_id']) && isset($_POST['user_lock']) )
{
  $user_id = $_POST['user_id'];
  $user_lock = NULL;

  if($_POST['user_lock'] == 'off')
  {
      $user_lock = 0;
  }
  else
  {
      $user_lock = 1;
  }

    $stmt = $pdo->prepare("UPDATE `cs_site_users` SET `enable`=:enable  WHERE  `id`=:user_id");

    $stmt->bindParam(':enable', $user_lock);
    $stmt->bindParam(':user_id', $user_id);

    $user_lock = $user_lock;
    $user_id = $user_id;

    $stmt->execute();


}






// Change the user group

if( isset($_POST['user_id']) && isset($_POST['group_id']) )
{
    $group_id = htmlspecialchars($_POST['group_id']);
    $user_id = htmlspecialchars($_POST['user_id']);

    $stmt = $pdo->prepare("UPDATE `cs_site_users` SET `group_id`=:group_id  WHERE  `id`=:user_id");

    $stmt->bindParam(':group_id', $group_id );
    $stmt->bindParam(':user_id', $user_id );

    $group_id = $group_id;
    $user_id = $user_id;

    $stmt->execute();
}





// Change discount_group for categories

if( isset($_POST['discount_cat_id']) && isset($_POST['menu_id_to_change_for']) )
{

    $discount_id = (int)$_POST['discount_cat_id'];
    $content_id = (int)$_POST['menu_id_to_change_for'];

    $stmt = $pdo->prepare("UPDATE `cs_content_list` SET `discount_id`=:discount_id  WHERE  `content_id`=:content_id");

    $stmt->bindParam(':discount_id', $discount_id );
    $stmt->bindParam(':content_id', $content_id );

    $discount_id = $discount_id;
    $content_id = $content_id;

    $stmt->execute();
}



?>

