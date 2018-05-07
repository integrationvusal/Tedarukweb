<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $pdo->query("UPDATE `cs_content_list` SET `content_delete` = 'yes' WHERE `content_id` = ".intval($_GET['delete'])." LIMIT 1");
    echo '<script type="text/javascript" language="javascript">window.location = "index.php?page=menu"</script>';
}
if(@$_POST['deleteSelected']) {
    $forDeleting = "";
    foreach($_POST['selected'] as $d) {
        $d = intval($d);
        $forDeleting .= $d.',';
    }
    $pdo->query("UPDATE `cs_content_list` SET `content_delete` = 'yes' WHERE `content_id` IN(".substr($forDeleting,0,strlen($forDeleting)-1).")");
    echo '<script type="text/javascript" language="javascript">window.location = "index.php?page=menu"</script>';
}
?>

<script>
    $(document).ready(function() {

        // mass removal start
        total_sum = 0;
        var mass_delete_btn = $('.mass_removal');

        $("table tr td input[type=checkbox]").click(function(){
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
                mass_delete_btn.removeClass('mass_remove_disable');
            } else {
                checkboxes.removeAttr('checked');
                mass_delete_btn.addClass('mass_remove_disable');
            }
        });
    })
</script>

<div class="content-box">
    <div class="content-box-header">
        <h3>Меню</h3>

        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск в меню" value="<?=@$_POST['search_query'];?>" />
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

        $name_ord_sel = (@$_GET['order'] == 'ASC') ? 'DESC' : 'ASC';
        $name_sort_sel = (@$_GET['sort'] == 'name') ? 'content_pagetitle_' . DEFAULT_LANG_DIR : 'content_under_menu';

        $query = $pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_show_on_menu`='yes' ORDER BY " . $name_sort_sel . " " . $name_ord_sel);
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));


        $group_query = $pdo->query("SELECT * FROM `cs_user_group`")->fetchAll();

        if ($setir > 0) {


            ?>


        <form name="menu_list" action="" method="POST">
            <input type="submit" name="deleteSelected" value="Удалить выбранные" class="mass_removal mass_remove_disable"/>
            <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th><input id="selectall" type="checkbox" /></th>
                    <th id="menu_list_id">ID</th>
                    <th id="menu_list_name">Название</th>
                    <th id="menu_list_discount_gr">Группа скидок</th>
                    <th id="menu_list_enable">Доступно</th>
                    <th id="menu_list_added">Добавлено</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                <?php
 

                $link_sc = "index.php?page=menu";

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
                            /*$search_key_arr = array();                            // Empty array
                            $table_row_arr = array();                             // array for table rows
                            $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces
*/

/*
                            // table rows
                            $table_row_arr[] = 'content_pagetitle_ru';
                            //$table_row_arr[] = 'content_description_ru';
                            //$table_row_arr[] = 'content_text_ru';

                            $sql_part1 = " AND ( `".$table_row_arr[0]."` ";
                           // $sql_part2 = "`".$table_row_arr[1]."` ";
                           // $sql_part3 = "`".$table_row_arr[2]."` ";

                            foreach( $search_key_arr_explode as $key=>$value )
                            {
                                if( $key == 0 )
                                {
                                    $sql_part1 .= "LIKE '%". $value."%'";
                                   // $sql_part2 .= "LIKE '%". $value."%'";
                                   // $sql_part3 .= "LIKE '%". $value."%'";
                                }
                                else
                                {
                                    $sql_part1 .= " OR `".$table_row_arr[0]."` LIKE '%".$value."%'";
                                   // $sql_part2 .= " OR `".$table_row_arr[1]."` LIKE '%".$value."%'";
                                    //$sql_part3 .= " OR `".$table_row_arr[2]."` LIKE '%".$value."%'";
                                }

                            }

                            //$sql = $pdo->query("SELECT * FROM `cs_publish` ".$sql_part1 ." OR ". $sql_part2 ." OR ". $sql_part3);

                            //$additional_sql = $sql_part1 ." OR ". $sql_part2 ." OR ". $sql_part3;*/
                            $additional_sql =  "AND (`content_pagetitle_ru` LIKE ".$pdo->quote("%". $str."%")."  OR `content_pagetitle_en` LIKE ".$pdo->quote("%". $str."%")."  OR `content_description_ru` LIKE ".$pdo->quote("%". $str."%")."  OR `content_description_en` LIKE ".$pdo->quote("%". $str."%")." OR `content_text_ru` LIKE ".$pdo->quote("%". $str."%")."  OR `content_text_en` LIKE ".$pdo->quote("%". $str."%")." )";




                        }

                        else
                        {
                            $additional_sql = '';
                        }
                        // search code end






                        $sql = $pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_show_on_menu`='yes' ".$additional_sql."  ORDER BY " . $name_sort_sel . " " . $name_ord_sel . " LIMIT " . ($count * $j) . " ," . ($count) . "");



                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);


                        //while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {


                        foreach( $row as $row1 ){

                           // echo $row1['discount_id'];


                            $block = ($row1['content_hide_page'] == 'no') ? '<span style="color: green;">Доступно</span>' : '<span style="color: red;">Скрытый</span>';



                            ?>
                            <tr>
                                <td><input type="checkbox" name="selected[]"  value="<?php echo $row1['content_id']; ?>" /></td>
                                <td width="5%"><?php echo $row1['content_id']; ?></td>
                                <td><?php echo $row1['content_pagetitle_ru']; ?></td>



                                <td>

                                    <?php
                                     if($row1['content_page_type'] == 'category')
                                     {?>
                                         <select name="<?php echo $row1['content_id'];?>" class="group_selectors">

                                             <option value="0">---</option>
                                             <?php
                                             foreach( $group_query as $groups )
                                             {?>

 <option <?php echo ($row1['discount_id'] == $groups['id']) ? 'selected="selected"' :'' ; ?>  value="<?php echo $groups['id']; ?>"><?php echo $groups['gr_name']; ?></option>
                                             <?}
                                             ?>
                                         </select>

                                     <?}?>

                                </td>



                                <td><?php echo $block; ?></td>
                                <td><?php echo date("d.m.Y - H:i", strtotime($row1['content_ins_date'])); ?></td>
                                <td width="12%">
                                    <a href="index.php?page=edit_resource&id=<?php echo $row1['content_id']; ?>&rsp=<?php echo htmlspecialchars($_GET['st']); ?>"><img
                                            src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/></a>
                                    <a onclick="return confirm('Вы уверены что хотите удалить ?')"
                                       href="index.php?page=menu&st=<?php echo htmlspecialchars($_GET['st']); ?>&order=<?php echo @$_GET['order']; ?>&sort=<?php echo @$_GET['sort']; ?>&delete=<?php echo $row1['content_id']; ?>"
                                       title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png"
                                                            alt="Delete"/></a>
                                    <span class="loader" style="visibility: hidden;"><img src='templates/default/images/2.gif'></span>
                                </td>
                            </tr>
                        <?php


                        }  // menu list foreach end
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




                $('.group_selectors').change(function(){

                    $(this).parent().parent().find('.loader').css('visibility','visible');

                    var cat_id = $(this).attr('name');
                    var group_id = $(this).val();

                    $.ajax({
                        'url':'actions/pages/get_more_info_ajax.php',
                        'type':'POST',
                        'data':'discount_cat_id=' + group_id + '&' + 'menu_id_to_change_for=' + cat_id,
                        success: function(success){
                            $('.loader').css('visibility','hidden');

                        }
                    }); // ajax query end

                }); // change handle end


                var row_id       = $('#menu_list_id');          // set cookoe value equal to 0
                var row_name     = $('#menu_list_name');        // set cookoe value equal to 1
                var row_discount = $('#menu_list_discount_gr'); // set cookoe value equal to 2
                var row_enable   = $('#menu_list_enable');      // set cookoe value equal to 3
                var row_added    = $('#menu_list_added');       // set cookoe value equal to 4

                row_id.click(function(){
                    $.cookie('cookie_menu_sort_row', '1', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_menu_sort_row', '2', { expires: 7 });
                });

                row_discount.click(function(){
                    $.cookie('cookie_menu_sort_row', '3', { expires: 7 });
                });

                row_enable.click(function(){
                    $.cookie('cookie_menu_sort_row', '4', { expires: 7 });
                });

                row_added.click(function(){
                    $.cookie('cookie_menu_sort_row', '5', { expires: 7 });
                });



                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_menu_sort_row'),0]],
                    headers:{
                        0: {sorter: false },
                        6: {sorter: false }
                    }
                }); // sorter end




            }); // ready end
        </script>
    </div>
</div>