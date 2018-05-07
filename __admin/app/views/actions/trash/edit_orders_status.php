<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Редактировать статус</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

        if(isset($_GET['id']))
        {
            $id = (int)$_GET['id'];
        }
        else
        {
            die('Отсутствуют параметры');
        }

        $select_q = $pdo->query("SELECT * FROM `cs_orders_status` WHERE `id`=".$pdo->quote($id)." ");

        if( isset($_POST['add_status']) && $_POST['status_name_az'] != ''  && $_POST['status_name_en'] != ''  && $_POST['status_name_ru'] != '' )
        {


            $stmt = $pdo->prepare(" UPDATE `cs_orders_status` SET `name_ru`=:name_ru , `name_az`=:name_az, `name_en`=:name_en ");

            $stmt->bindParam(':name_ru', $name_ru);
            $stmt->bindParam(':name_az', $name_az);
            $stmt->bindParam(':name_en', $name_en);

            $name_ru = htmlspecialchars($_POST['status_name_ru']);
            $name_az = htmlspecialchars($_POST['status_name_az']);
            $name_en = htmlspecialchars($_POST['status_name_en']);

            $stmt->execute();

        }

        else{?>
            <form name="add_status" action="" method="POST">

                <p>
                    <label >Название статуса (AZ)</label>
                    <input class="text-input small-input" value="" type="text" name="status_name_az" />
                </p>
                <p>
                    <label >Название статуса (RU)</label>
                    <input class="text-input small-input" value="" type="text" name="status_name_ru" />
                </p>
                <p>
                    <label >Название статуса (EN)</label>
                    <input class="text-input small-input" value="" type="text" name="status_name_en" />
                </p>

                <p>
                    <input class="button" type="submit" name="add_status" value="Сохранить" />
                </p>

            </form>
        <? }?>



        <div class="clear"></div>
    </div>
</div>