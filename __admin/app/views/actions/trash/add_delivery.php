<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if( isset($_POST['add_delivery']) )
{
    $delivery_name_ru = htmlspecialchars( $_POST['new_delivery'] );
    $delivery_name_en = htmlspecialchars( $_POST['new_delivery_en'] );
    $delivery_name_az = htmlspecialchars( $_POST['new_delivery_az'] );
    $amount = htmlspecialchars( $_POST['delivery_amount'] );
    $unit = htmlspecialchars( $_POST['unit'] );

    $error_amount = 0;

    if( empty($delivery_name_ru)  )
    {
        $error_amount++;
    }

    if( empty($amount) )
    {
        $error_amount++;
    }


    if( !(is_numeric($amount) ))
    {
        $error_amount++;
    };



    if( empty($delivery_name_en) )
    {
        $error_amount++;
    }


    if( empty($delivery_name_az) )
    {
        $error_amount++;
    }




    if( $error_amount > 0 )
    {
        //echo 'ERROR';
    }
    else
    {
        //echo 'ALL RIGHT';
        //Еще вариант
        $stmt = $pdo->prepare("INSERT INTO `cs_delivery` (`name_ru`, `name_en`, `name_az`, `amount`, `unit`) VALUE (:name_ru, :name_en , :name_az , :amount, :unit)");

        $stmt->bindParam(':name_ru', $name_ru);
        $stmt->bindParam(':name_en', $name_en);
        $stmt->bindParam(':name_az', $name_az);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':unit', $unit);

        $name_ru = $delivery_name_ru;
        $name_en = $delivery_name_en;
        $name_az = $delivery_name_az;
        $amount = $amount;
        $unit = $unit;

        $stmt->execute();
    }
    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=list_delivery'</script>";

}



?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Новая доставка</h3>
        <div class="clear"></div>
    </div>


    <div class="content-box-content">
        <?php
        if( @$error_amount > 0 )
        {
            echo '<div class="error_div">Заполнены не все поля или заполнены неправильно</div>';
        }
        ?>
            <form name="deliver_creating" action="" method="POST">

                <p>
                    <label >Название доставки (RU)</label>
                    <input class="text-input small-input" value="" type="text" name="new_delivery"/>
                </p>
                <p>
                    <label >Название доставки (EN)</label>
                    <input class="text-input small-input" value="" type="text" name="new_delivery_en"/>
                </p>
                <p>
                    <label >Название доставки (AZ)</label>
                    <input class="text-input small-input" value="" type="text" name="new_delivery_az"/>
                </p>
                <p>
                    <label >Величина</label>
                    <input class="text-input small-input" value="" type="text" name="delivery_amount" />

                    <select name="unit">
                        <option value="%">%</option>
                        <option value="azn">AZN</option>
                    </select>
                </p>

                <p>
                    <input class="button" type="submit" name="add_delivery" value="Сохранить">
                </p>

            </form>

        <div class="clear"></div>
    </div>
</div>