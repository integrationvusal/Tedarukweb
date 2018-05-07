<?php

	if(!defined("_VALID_PHP"))
        die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Добавить скидочную группу</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php
        if( isset($_POST['create_group']) && !empty($_POST['group_name']) && !empty($_POST['group_discount']) )
        {

            $date = date("Y-m-d");


            $stmt = $pdo->prepare("INSERT INTO `cs_user_group` (`gr_name`, `gr_discount`, `gr_ins_date`) VALUE (:gr_name, :gr_discount, :gr_ins_date)");

            $stmt->bindParam(':gr_name', $gr_name);
            $stmt->bindParam(':gr_discount', $gr_discount);
            $stmt->bindParam(':gr_ins_date', $gr_ins_date);

            $gr_name = $_POST['group_name'];
            $gr_discount = $_POST['group_discount'];
            $gr_ins_date = $date;

            $stmt->execute();

            if($stmt)
            {
                echo '<script language="javascript" type="text/javascript">window.location = "index.php?page=list_user_group";</script>';
            }
        }
        else
        {?>
            <form name="add_group" action="" method="POST">
                <p>
                    <label>Название группы</label>
                    <input type="text" name="group_name" class="text-input small-input"/>
                </p>
                <p>
                    <label>Скидка</label>
                    <input type="text" name="group_discount" class="text-input small-input"/>&nbsp;%
                </p>
                <p>
                    <input class="button" type="submit" name="create_group" value="Сохранить">
                </p>
            </form>
        <?}?>


    </div>
</div>