<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');
    
if(@$_POST['deleteSelected']) {
    $forDeleting = "";
    foreach($_POST['selected'] as $d) {
        $d = intval($d);
        $forDeleting .= $d.',';
    }
    $pdo->query("UPDATE `cs_product` SET `enable` = 0 WHERE `id` IN(".substr($forDeleting,0,strlen($forDeleting)-1).")");
    echo '<script type="text/javascript" language="javascript">window.location = "index.php?page=view_product"</script>';
}

?>

<script>
    $(document).ready(function() {

        // mass removal start
        total_sum = 0;
        var mass_delete_btn = $('.mass_removal');

        $("table tr td input[type=checkbox] , #selectall").click(function(){
            total_sum = $("table tr td input[type=checkbox]:checked").length;
            console.log(total_sum);

            if( total_sum > 0 )
            {
                mass_delete_btn.removeClass('mass_remove_disable');
            }
            else
            {
                mass_delete_btn.addClass('mass_remove_disable');
            }
        });

        mass_delete_btn.click(function(event){
            total_sum = $("table tr td input[type=checkbox]:checked").length;
            if( total_sum == 0 )
            {
                event.preventDefault();
            }
            else
            {
                return confirm('Вы уверены что хотите удалить ' + total_sum + ' шт. ?');
            }
        });


        // mass removal end





        $('#selectall').change(function() {
            var checkboxes = $(this).closest('form').find(':checkbox');
            if($(this).is(':checked')) {
                checkboxes.attr('checked', 'checked');
            } else {
                checkboxes.removeAttr('checked');
            }
        });
        
        $(".makeDayProduct").click(function(event) {
            event.preventDefault();

            var id = $(this).attr('data');
            var val = $(this).attr('data-enable');
            $.ajax({
                'url':'actions/pages/dayandsale_ajax.php',
                'type':'POST',
                'data':'action=day&id=' + id + '&val=' + val,
                success: function(data){
                    if (data == true) {
                        $("#l_"+id).html('').html(val == 0 ? '<img src="<?=IMAGE_DIR;?>day_of_off.png" />' : '<img src="<?=IMAGE_DIR;?>day_of_on.png" />');
                        $("#l_"+id).attr("data-enable", val == 0 ? '1' : '0');
                    }
                }
            })
        })
        
        $(".makeSaleProduct").click(function(event) {
            event.preventDefault();

            var id = $(this).attr('data');
            var val = $(this).attr('data-enable');
            $.ajax({
                'url':'actions/pages/dayandsale_ajax.php',
                'type':'POST',
                'data':'action=sale&id=' + id + '&val=' + val,
                success: function(data){
                    if (data == true) {
                        $("#s_"+id).html('').html(val == 0 ? '<img src="<?=IMAGE_DIR;?>sale_of.png" />' : '<img src="<?=IMAGE_DIR;?>sale_on.png" />');
                        $("#s_"+id).attr("data-enable", val == 0 ? '1' : '0');
                    }
                }
            })
        })

    })
</script>

<div class="content-box">
    <div class="content-box-header">
        <h3>Список продуктов</h3>


        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск продуктов" value="<?=@$_POST['search_query'];?>" />
                <input type="submit" name="search_user" value="Поиск" />
            </form>
        </div>
        <!--searchbox end-->

    <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

        if (@$_GET['st'] == '') {
            $_GET['st'] = 1;
        }

        // $name_ord_sel = (@$_GET['order'] == 'ASC') ? 'DESC' : 'ASC';
        // $name_sort_sel = (@$_GET['sort'] == 'name') ? 'content_pagetitle_' . DEFAULT_LANG_DIR : 'content_under_menu';

        $query = $pdo->query("SELECT * FROM `cs_product` where `enable` = '1' ORDER BY `id`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));

        $brend_list = $pdo->query("SELECT * FROM `cs_brend_list`")->fetchAll(PDO::FETCH_ASSOC);

        if ($setir > 0) {


            ?>
            <form method="post" action="">
                <input type="submit" name="deleteSelected" value="Удалить выбранные"  class="mass_removal mass_remove_disable"/>
                <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                    <thead>
                    <tr>
                        <th><input id="selectall" type="checkbox" /></th>
                        <th id="product_list_id">ID</th>
                        <th id="product_list_name">Название</th>
                        <th id="product_list_brend">Бренд</th>
                        <th id="product_list_cat">Категория</th>
                        <th id="product_list_added">Добавлено</th>
                        <th id="product_list_enable">Доступность</th>
                        <th id="product_day">Продукт дня</th>
                        <th id="product_sale">Распродажа</th>
                        <th>Управление</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
    
    
                    $link_sc = "index.php?page=view_product";
    
                    $count=70;
                    $cnt=100;
                    $rpp=$count;
                    $rad=1;
    
                    if (isset($_GET['st'])){$page=$_GET['st']-1;}else{$page=0;}
    
                    $links=$rad*2+1;
                    $pages=ceil($setir/$rpp);
                    $start=$page-$rad;
    
                    if($start>$pages-$links){$start=$pages-$links;}
                    if($start<0){$start=0;}
                    $end=$start+$links;
    
                    if ($end>$pages){ $end=$pages; }
                    for ($j=$start; $j<$end; $j++){
                        if($j==$page){
    
    
    
    
    
                            // Search code start
                            if( isset($_POST['search_query']) && !empty($_POST['search_query']) )
                            {
    
                                $str = htmlspecialchars($_POST['search_query']);      // Query string
                                $search_key_arr = array();                            // Empty array
                                $table_row_arr = array();                             // array for table rows
                                $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces
    
    
    
                                // table rows
                                $table_row_arr[] = 'name_ru';
                                $table_row_arr[] = 'description_ru';
                                $table_row_arr[] = 'full_text_ru';
    
                                $sql_part1 = " AND ( `".$table_row_arr[0]."` ";
                                $sql_part2 = "`".$table_row_arr[1]."` ";
                                $sql_part3 = "`".$table_row_arr[2]."` ";
    
                                foreach( $search_key_arr_explode as $key=>$value )
                                {
                                    if( $key == 0 )
                                    {
                                        $sql_part1 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                        $sql_part2 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                        $sql_part3 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                    }
                                    else
                                    {
                                        $sql_part1 .= " OR `".$table_row_arr[0]."` LIKE ".$pdo->quote("%". $value."%")."";
                                        $sql_part2 .= " OR `".$table_row_arr[1]."` LIKE ".$pdo->quote("%". $value."%")."";
                                        $sql_part3 .= " OR `".$table_row_arr[2]."` LIKE ".$pdo->quote("%". $value."%")."";
                                    }
    
                                }
    
                                //$sql = $pdo->query("SELECT * FROM `cs_publish` ".$sql_part1 ." OR ". $sql_part2 ." OR ". $sql_part3);
    
                                $additional_sql = $sql_part1 ." OR ". $sql_part2 ." OR ". $sql_part3 ." )";
    
                            }
    
                            else
                            {
                                $additional_sql = '';
                            }
    
    
    
    
    
    
    
    
    
    
    
                           //  = $pdo->query("SELECT * FROM `cs_publish` LIMIT " . ($count * $j) . " ," . ($count) . "");
                            $sql = $pdo->query('
                                SELECT `cs_product`.`id`, `cs_product`.`product_day`, `cs_product`.`sale`, `brend_id` , `cs_product`.`name_ru`, `cs_product`.`added_date`, `cs_product`.`enable`, `cs_content_list`.`content_pagetitle_ru` , `cs_product`.`added_date`
                                FROM `cs_product`
                                LEFT JOIN `cs_content_list` ON `cs_content_list`.`content_id` = `cs_product`.`cat_id`
                                WHERE `cs_product`.`enable` = "1" AND `delete`= 0 '.$additional_sql.' LIMIT '.($count * $j) . " ," . ($count).'
                            ');
    
    
    
    
    
                            $result = $sql->fetchAll(PDO::FETCH_ASSOC);
    
    
    
    
    
    
    
                            foreach ($result as $k => $item) {
                                $sql = $pdo->query('
                                    SELECT `cs_products_cat`.`id`, `cs_content_list`.`content_pagetitle_ru`
                                    FROM `cs_products_cat`
                                    LEFT JOIN `cs_content_list` ON `cs_content_list`.`content_id` = `cs_products_cat`.`cat_id`
                                    WHERE `cs_products_cat`.`product_id` = '.$item['id'].'
                                ');
                                $result[$k]['categories'] = $sql->fetchAll(PDO::FETCH_ASSOC);
                            }
    
                            foreach( $result as $row ){
                                $block = ($row['enable'] == '1') ? '<span style="color: green;">Доступно</span>' : '<span style="color: red;">Скрытый</span>';
                                ?>
                                <tr>
                                    <td><input name="selected[]" type="checkbox" value="<?php echo $row['id'];?>" /></td>
                                    <td width="5%"><?=$row['id']; ?></td>
                                    <td><?=$row['name_ru']; ?></td>
                                    <td>
                                        <?php
                                            foreach( $brend_list as $current_blend )
                                            {
                                                if($current_blend['brend_id'] == $row['brend_id'])
                                                {
                                                    echo $current_blend['brend_name'];
                                                };
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php foreach ($row['categories'] as $item): ?>
                                            <?=$item['content_pagetitle_ru']; ?>
                                        <?php endforeach ?>
                                    </td>
                                    <td><?=date("d.m.Y - H:i", strtotime($row['added_date'])); ?></td>
                                    <td><?=$block; ?></td>

                                    <td style="text-align: center;">
                                        <a id="l_<?php echo $row['id'];?>" class="makeDayProduct" href="#" data="<?php echo $row['id'];?>" data-enable="<?=($row['product_day'] == 1 ? '0' : '1'); ?>"><?=($row['product_day'] == 1 ? '<img src="<?=IMAGE_DIR;?>day_of_on.png" />' : '<img src="<?=IMAGE_DIR;?>day_of_off.png" />'); ?></a>
                                    </td>

                                    <td style="text-align: center;">
                                        <a id="s_<?php echo $row['id'];?>" class="makeSaleProduct" href="#" data="<?php echo $row['id'];?>" data-enable="<?=($row['sale'] == 1 ? '0' : '1'); ?>"><?=($row['sale'] == 1 ? '<img src="<?=IMAGE_DIR;?>sale_on.png" />' : '<img src="<?=IMAGE_DIR;?>sale_of.png" />'); ?></a>
                                    </td>


                                    <td width="12%">
                                        <a href="index.php?page=edit_product&id=<?php echo $row['id']; ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/></a>
    
                                        <a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=delete_product&id=<?php echo $row['id']; ?>" title="Удалить">
                                            <img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete"/>
                                        </a>
                                        <span class="loader" style="visibility: hidden;"><img src="<?=IMAGE_DIR;?>2.gif"></span>
                                    </td>
                                </tr>
                            <?php
    
    
                            } // foreach end
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </form>
            <div class="pagination">
                <?php


                if ($page>0){echo "<a href=\"$link_sc&st=1\">« First</a><a href=\"$link_sc&st=".($page)."\">« Previous</a>";}
                for ($i=$start; $i<$end; $i++){
                    if($i==$page){echo '<a href="#" class="number current">';}
                    else {echo "<a class='number' href=\"$link_sc&st=".($i+1)."\">";}
                    echo ($i+1);

                    if($i==$page){echo "</b>";}else{echo "</a>";}
                    if ($i!=($end-1)) { echo ""; }
                }

                if($pages>$links&&$page<($pages-$rad-1)){echo " ... <a href=\"$link_sc&st=".($pages)."\">".($pages)."</a>"; }
                if ($page<$pages-1){ echo " <a  href=\"$link_sc&st=".($page+2)."\">Next »</a><a href=\"$link_sc&st=".($pages)."\">Last »</a>";}

                ?>
            </div>

        <?php

        } else {

            echo '<div class="no_table_info">Информация отсутствует</div>';

        }

        ?>

        <div class="clear"></div>


        <script type="text/javascript">
            $(document).ready(function()
            {
                var row_id               = $('#product_list_id');          // set cookoe value equal to 0
                var row_name             = $('#product_list_name');        // set cookoe value equal to 1
                var row_brend            = $('#product_list_brend');       // set cookie value equal to 2
                var row_cat              = $('#product_list_cat');         // set cookoe value equal to 3
                var row_enable           = $('#product_list_added');       // set cookoe value equal to 4
                var row_added            = $('#product_list_enable');      // set cookoe value equal to 5
                var row_day              = $('#product_day');              // set cookoe value equal to 6
                var row_sale              = $('#product_sale');            // set cookoe value equal to 7

                row_id.click(function(){
                    $.cookie('cookie_product_sort_row', '1', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_product_sort_row', '2', { expires: 7 });
                });

                row_brend.click(function(){
                    $.cookie('cookie_product_sort_row', '3', { expires: 7 });
                });

                row_cat.click(function(){
                    $.cookie('cookie_product_sort_row', '4', { expires: 7 });
                });

                row_enable.click(function(){
                    $.cookie('cookie_product_sort_row', '5', { expires: 7 });
                });

                row_added.click(function(){
                    $.cookie('cookie_product_sort_row', '6', { expires: 7 });
                });
                
                row_added.click(function(){
                    $.cookie('cookie_product_day', '7', { expires: 7 });
                });

                row_added.click(function(){
                    $.cookie('cookie_product_sale', '8', { expires: 7 });
                });




                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_product_sort_row'),0]],
                    headers:{
                        0: {sorter: false },
                        9: {sorter: false }
                    }
                }); // sorter end

            }); // ready
        </script>
    </div>
</div>