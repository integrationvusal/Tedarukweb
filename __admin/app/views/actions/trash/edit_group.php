<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Редактировать скидочную группу</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

       $id = (int)$_GET['id'];


           // getting data
       if( isset($_GET['action']) && $_GET['action'] == 'edit' )
       {$query = $pdo->query("SELECT * FROM `cs_user_group` WHERE `id`=".$pdo->quote($id)."")->fetch(PDO::FETCH_ASSOC); ?>
           <form name="add_group" action="" method="POST">
               <p>
                   <label>Название группы</label>
                   <input type="text" name="group_name" class="text-input small-input" value="<?=$query['gr_name'];?>" />
               </p>
               <p>
                   <label>Скидка</label>
                   <input type="text" name="group_discount" class="text-input small-input" value="<?=$query['gr_discount'];?>" />&nbsp;%
               </p>
               <p>
                   <input class="button" type="submit" name="save_group" value="Сохранить">
               </p>
           </form>
       <?}


       // deleting data
       if(  isset($_GET['action']) && $_GET['action'] == 'delete' )
       {
            $delete_query = $pdo->exec("DELETE FROM `cs_user_group` WHERE `id`=".$pdo->quote($id)."");
           if($delete_query)
           {
                echo '<script language="javascript" type="text/javascript">window.location = "index.php?page=list_user_group";</script>';
           }
       }


        // saving changes
        if( isset($_POST['save_group']) )
        {
            $ins_query = $pdo->prepare("UPDATE `cs_user_group` SET `gr_name`= :gr_name , `gr_discount`= :gr_discount WHERE `id`=".$pdo->quote($id)."");

            $ins_query->bindParam(':gr_name' , $gr_name);
            $ins_query->bindParam(':gr_discount' , $gr_discount);

            $gr_name = $_POST['group_name'];
            $gr_discount = $_POST['group_discount'];

            $success = $ins_query->execute();

            if($success)
            {
                echo '<script language="javascript" type="text/javascript">window.location = "index.php?page=list_user_group";</script>';
            }

        } // saving changes end



        ?>




    </div>
</div>