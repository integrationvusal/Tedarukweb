<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');




if(isset($_GET['id']) && !empty($_GET['id']) )
{
    $id = (int)$_GET['id'];
}

$query1 = $pdo->query("SELECT * FROM `cs_orders` WHERE `id`=".$pdo->quote($id)."")->fetch(PDO::FETCH_ASSOC);


// переменная связывающая таблицы `cs_orders` и  `cs_order_products`
$order_products_id = $query1['order_products_id'];


$query2 = $pdo->query("SELECT * FROM `cs_order_products` WHERE `order_id`=".$order_products_id." ")->fetchAll(PDO::FETCH_ASSOC);



if(isset($_POST['save']))
{
    $res = $pdo->prepare("UPDATE `cs_orders` SET `status`= :status , `tag_id` = :tag_id WHERE `id`= :id ");

    $res->bindParam(':status' , $status);
    $res->bindParam(':id' , $id);
    $res->bindParam(':tag_id' , $tag_id);

    $status = $_POST['order_status'];
    $tag_id = (int)$_POST['tag_id'];
    $id = $id;

    $res->execute();

    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=new_orders'</script>";

}
?>


<div class="content-box">
<div class="content-box-header">
    <h3>Заказ N<?=(int)$_GET['id']?></h3>

    <div class="clear"></div>
</div>
<div class="content-box-content" style="min-height: 300px;">




<form name="each_order" id="each_order" action="" method="POST">

    <p>
        <?php
        $query5 = $pdo->query("SELECT * FROM `cs_orders_status`")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <b>Статус заказа :</b>

        <select name="order_status">
            <option value="0">---</option>
            <?php

            foreach($query5 as $res5)
            {?>
                <option value="<?=$res5['id']?>" <?php echo ( $query1['status'] == $res5['id'] ) ? ' selected="selected"' :'' ; ?> > <?=$res5['name_ru']?></option>
            <?}
            ?>

        </select>
    </p>

    <p>
        <?php
        $query7 = $pdo->query("SELECT * FROM `cs_tags`")->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <b>Метка :</b>
        <select name="tag_id">
            <option value="0">---</option>
            <?php

            foreach($query7 as $query7)
            {?>
                <option value="<?=$query7['id']?>" <?php echo ( $query1['tag_id'] == $query7['id'] ) ? ' selected="selected"' :'' ; ?> > <?=$query7['name']?></option>
            <?}
            ?>

        </select>
    </p>


    <div class="order_information">
        <p>
            <b>Имя</b> : <?=$query1['name']?>
        </p>
        <p>
            <b>Фамилия</b> : <?=$query1['surname']?>
        </p>
        <p>
            <b>Телефон</b> :<input type="text" name="edit_user_phone" class="text-input" value="<?=$query1['phone']?>" />
        </p>

        <?php if ($query1['order_type'] == 'credit'): ?>
        <p>
           <a id="credit_data" href="#">Данне кердита</a>
        </p>


        <div id="credit_block" style="display: none;">
            <p>
                <b>Отчество</b> : <?php echo $query1['father_name'] ?>
            </p>
            <p>
                <b>Номер пасспорта</b> : <?php echo $query1['serie'].$query1['serie_number'] ?>
            </p>
            <p>
                <b>День рождения</b> : <?php echo $query1['birthday'] ?>
            </p>
            <p>
                <b>Место рождения</b> : <?php echo $query1['birthday_place'] ?>
            </p>
            <p>
                <b>Послдение символы (пасспорт)</b> : <?php echo $query1['last_simbols'] ?>
            </p>
            <p>
                <b>Семейное положение</b> : <?php echo $query1['married_status'] ?>
            </p>
            <p>
                <b>Место проживания</b> : <?php echo $query1['live_place'] ?>
            </p>
            <p>
                <b>Выдал удостоверение</b> : <?php echo $query1['department_name'] ?>
            </p>

            <p>
                <b>Дата истечения срока(пасспорта)</b> : <?php echo $query1['passport_date'] ?>
            </p>

            <p>
                <b>Email</b> : <?php echo $query1['email'] ?>
            </p>
            <p>
                <b>Период редита (в месяцах)</b> : <?php echo $query1['credit_period'] ?>
            </p>
            <p>
                <b>Первый взнос</b> : <?php echo $query1['first_amount'] ?>
            </p>
            <p>
                <b>Ежемесечная оплата</b> : <?php echo $query1['month_amount'] ?>
            </p>




        </div>


        <?php endif ?>
        <p>
            <b>Адрес</b> :<br />
            <textarea name="address" style="width:270px !important; height: 100px !important;" class="text-input"> <?=$query1['address']?></textarea>
            <!--<input type="text" name="edit_user_address" class="text-input" value="<?=$query1['address']?>" />-->
        </p>
        </p>
        <b>Дата</b> : <?=gmdate("d-m-Y", strtotime($query1['date']))?>
        </p>
        <p>
        <b>Время</b> : <?=gmdate("H:i:s", strtotime($query1['date']))?>
        </p>
    </div>


    <p>
        <?php
        $ref = $query1['delivery_type'];
        $query6 = $pdo->query("SELECT `name_ru` FROM `cs_delivery` WHERE `id`=".$pdo->quote($ref)."")->fetch(PDO::FETCH_ASSOC);
        ?>
        <b>Вид доставки</b>: <?=$query6['name_ru'];?>
    </p>


    <p>
        <?php
        $payment_method_arr = array();
        $payment_method_arr[0] = 'Оплата Банковской картой';
        $payment_method_arr[1] = 'Наличный расчет';
        ?>
        <b>Способы оплаты:</b>: <?=$payment_method_arr[$query1['payment_type']]?>
    </p>




    <p><b>Заказанные продукты:</b></p>
    <ul class="orderred_prod_list">

        <?php
        /* foreach( $query2 as $query2 )
         {
           $query3 = $pdo->query("SELECT * FROM `cs_product` WHERE `id`=".$query2['product_id']."")->fetch(PDO::FETCH_ASSOC);
             $query4 = $pdo->query("SELECT * FROM `cs_content_list` WHERE `content_id`=".$query3['cat_id']."")->fetch(PDO::FETCH_ASSOC);
         ?>
             <? echo "SELECT * FROM `cs_content_list` WHERE `content_id`=".$query3['cat_id'].""; ?>
            <li><a href="http://amazon.siteman.az/ru/category/<?=$query4['url']?>/product/<?=$query3['id']?>" target="_blank"><?=$query3['name_ru']?></a> &nbsp; (<?=$query2['quantity']?> шт.)</li>
         <?}*/?>


        <?php

        foreach($query2 as $arr)
        {
            $asc = $pdo->query("SELECT * FROM `cs_product` WHERE `id`=".$pdo->quote($arr['product_id'])."")->fetch(PDO::FETCH_ASSOC);
            //echo "SELECT * FROM `cs_content_list` WHERE `content_id`=".$asc['cat_id']."";

            //echo "SELECT * FROM `cs_product` WHERE `id`=".$arr['product_id']."";
           /* echo "
            SELECT `cs_product`.* FROM `cs_product`
            LEFT JOIN `cs_products_cat` ON `cs_product`.`id`=`cs_products_cat`.`product_id`
            LEFT JOIN  `cs_content_list` ON `cs_products_cat`.`cat_id`=`cs_content_list`.`content_id` LIMIT 1";


            die();*/

            $query4 = $pdo->query("
            SELECT `cs_product`.*, `cs_content_list`.`url` FROM `cs_product`
            LEFT JOIN `cs_products_cat` ON `cs_product`.`id`=`cs_products_cat`.`product_id`
            LEFT JOIN  `cs_content_list` ON `cs_products_cat`.`cat_id`=`cs_content_list`.`content_id`")->fetch(PDO::FETCH_ASSOC);

/*

echo '<pre>';
print_r($query4);
            echo '</pre>';*/

            ?>
            <li><a href="http://amazon.siteman.az/ru/category/<?=$query4['url']?>/product/<?=$arr['product_id']?>" target="_blank"><?=$asc['name_ru']?></a>&nbsp;&nbsp;(<?=$arr['quantity']?> шт.)</li>
        <? } ?>


    </ul>
    <div class="total_price">
        <p><b>Общая сумма: <?=$query1['price']?></b></p>
    </div>

    <p>
        <b>Google Maps:</b>&nbsp;&nbsp;&nbsp;&nbsp;<span class="hint">Вставьте iframe  код в поле</span>
        <br />
        <textarea class="medium-input" rows="5" name="google_maps"></textarea>
    </p>


    <p>
        <b>Примечание:</b><br />
        <textarea class="medium-input" rows="9" name="notes"></textarea>
    </p>

    <p>
        <!-- <input type="submit" name="print_ver" id="print_ver" value="Печатать дорожную квитанцию" class="button"  />-->
        &nbsp;&nbsp;
        <input type="submit" name="save" value="Сохранить" class="button" />
        &nbsp;&nbsp;
        <!--<a href="#" class="a_button_imit" id="print_ver_submit_btn">Печатать дорожную квитанцию</a>-->
        <input type="button" class="button" id="print_ver_submit_btn" value="Печатать дорожную квитанцию" />
    </p>

</form>


<?php
/*if(isset($_POST['print_it']))
{
   echo '<pre>';
     print_r($_POST);
   echo '</pre>';

    die();
}*/
?>


<form name="hidden_form_for_print_ver" action="http://amazon.siteman.az/amazonmanager/actions/pages/order_print_ver.php" method="POST">


    <p style="display: none; visibility: hidden;">
        <input type="text" name="total_sum" value="<?=$query1['price']?>" />
    </p>

    <p style="display: none; visibility: hidden;">name<br /><input type="text" name="name" value="<?=$query1['name']?>"/></p>
    <p style="display: none; visibility: hidden;">lastname<br /><input type="text" name="lastname" value="<?=$query1['surname']?>"/></p>
    <p style="display: none; visibility: hidden;">phone<br /><input type="text" name="phone" /></p>

    <p style="display: none; visibility: hidden;"> ID квитанции:<br /><input type="text" name="id" value="<?=$query1['id']?>" /></p>


        <?php

        foreach($query2 as $arr)
        {
        $asc = $pdo->query("SELECT * FROM `cs_product` WHERE `id`=".$pdo->quote($arr['product_id'])."")->fetch(PDO::FETCH_ASSOC);

        $query4 = $pdo->query("
            SELECT `cs_product`.*, `cs_content_list`.`url` FROM `cs_product`
            LEFT JOIN `cs_products_cat` ON `cs_product`.`id`=`cs_products_cat`.`product_id`
            LEFT JOIN  `cs_content_list` ON `cs_products_cat`.`cat_id`=`cs_content_list`.`content_id`")->fetch(PDO::FETCH_ASSOC);

        ?>
            <!--<li><a href="http://amazon.siteman.az/ru/category/<?=$query4['url']?>/product/<?=$arr['product_id']?>" target="_blank"><?=$asc['name_ru']?></a>&nbsp;&nbsp;(<?=$arr['quantity']?> шт.)</li>-->
    <p>
        <input style="display: none; visibility: hidden;" type="text" name="ordered_products[]" value="<?=$asc['name_ru']?>" /> &nbsp;
        <input style="display: none; visibility: hidden;" type="text" name="ordered_product_count[]" value="<?=$arr['quantity']?>" />
        <input style="display: none; visibility: hidden;" type="text" name="ordered_product_price[]" value="<?=$asc['price']?>" />
    </p>

    <?}?>



    <p style="display: none; visibility: hidden;">address<br /><textarea name="address" ></textarea></p>



    <p style="display: none; visibility: hidden;">google map<br /><textarea name="google_map"></textarea></p>
    <p style="display: none; visibility: hidden;">admin notes<br /><textarea name="notes"></textarea></p>

    <p style="display:inline-block;  position: relative; top: -72px; left: 100px">
        <!--<input type="submit"  name="print_it" class="button" value="Печатать дорожную квитанцию" /></p>-->


</form>



<script type="text/javascript" language="javascript">
    $(document).ready(function(){
        $('#print_ver_submit_btn').click(function(){
            $('form[name=hidden_form_for_print_ver]').submit();
        });
    });
</script>

<script type="text/javascript" languahe="javascript">
    $(document).ready(function(){

        $('#credit_data').bind('click', function (e) {
            $('#credit_block').bPopup({
                follow: false,
                modalColor: '#ffffff',
                opacity: 0.7,
                closeClass: 'popup_close'
            });
        });

        //Форма откуда будут браться данные
        var each_order = $('form[name=each_order]');

        var phone = each_order.find('input[name=edit_user_phone]').val();
        var address = each_order.find('textarea[name=address]').val();
        var google_maps = each_order.find('textarea[name=google_maps]') ;
        var notes = each_order.find('textarea[name=notes]') ;


        var print_form_submit = $('form[name=hidden_form_for_print_ver] input[type=submit]');



        //Форма куда будут вставляться данные
        var print_form = $('form[name=hidden_form_for_print_ver]');

        var insert_phone = print_form.find('input[name=phone]');
        var insert_address = print_form.find('textarea[name=address]');
        var insert_google_map = print_form.find('textarea[name=google_map]');
        var insert_notes = print_form.find('textarea[name=notes]');

        insert_phone.val(phone);
        insert_address.val(address);





        print_form_submit.click(function(event){
            insert_google_map.text(google_maps.val());
            insert_notes.text(notes.val());
        });// click end

    });// ready end
</script>

<div class="clear"></div>
</div>
</div>