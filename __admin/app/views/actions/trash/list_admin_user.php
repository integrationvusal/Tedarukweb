<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Список админ пользователей</h3>




        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск пользователей" value="<?=@$_POST['search_query'];?>" />
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

        $query = $pdo->query("SELECT * FROM `cs_admin_list`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));



        if ($setir > 0) {


            ?>



            <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th id="admin_user_id">ID</th>
                    <th id="admin_user_name">Имя</th>
                    <th id="admin_user_enable">Доступ</th>
                    <th id="admin_user_reg_date">Время регистрации</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                <?php


                $link_sc = "index.php?page=list_admin_user";

                $count=10;
                $cnt=100;
                $rpp=10;
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

                        $sql = $pdo->query("SELECT * FROM `cs_admin_list`  LIMIT " . ($count * $j) . " ," . ($count) . "");




                        // Search code start
                        if( isset($_POST['search_user']) && !empty($_POST['search_query']) )
                        {

                            $str = htmlspecialchars($_POST['search_query']);      // Query string
                            $search_key_arr = array();                            // Empty array
                            $table_row_arr = array();                             // array for table rows
                            $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces



                            // table rows
                            $table_row_arr[] = 'admin_name';
                            $table_row_arr[] = 'admin_surname';

                            $sql_part1 = " WHERE `".$table_row_arr[0]."` ";
                            $sql_part2 = "`".$table_row_arr[1]."` ";

                            foreach( $search_key_arr_explode as $key=>$value )
                            {
                                if( $key == 0 )
                                {
                                    $sql_part1 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                    $sql_part2 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                }
                                else
                                {
                                    $sql_part1 .= " OR `".$table_row_arr[0]."` LIKE ".$pdo->quote("%". $value."%")."";
                                    $sql_part2 .= " OR `".$table_row_arr[1]."` LIKE ".$pdo->quote("%". $value."%")."";
                                }

                            }

                            $sql = $pdo->query("SELECT * FROM `cs_admin_list` ".$sql_part1 ." OR ". $sql_part2);

                        }

                        else
                        {

                        }
                        // search code end



                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);

                        foreach( $row as $row ){

                            $block = ($row['admin_block'] == 'no') ? '<span style="color: green;">Доступно</span>' : '<span style="color: red;">Блокирован</span>';


                            ?>
                            <tr>
                                <td width="5%"><?php echo $row['admin_id']; ?></td>
                                <td><?php echo ($row['admin_type'] == 'admin') ? '<b>' :'' ; ?><?php echo $row['admin_name']; ?><?php echo ($row['admin_type'] == 'admin') ? '</b>  <span style="color: #999;">(Администратор)</span>' :'' ; ?></td>
                                <td><?php echo $block; ?></td>
                                <td><?=date("d.m.Y - H:i", strtotime($row['admin_reg_date'])); ?></td>

                                <td width="12%">
                                    <a href="index.php?page=edit_admin_user&id=<?php echo $row['admin_id'];?>">
                                        <img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/>
                                    </a>

                                    <?php if( $row['admin_type'] != 'admin' ){?>
                                        <a onclick="return confirm('Вы уверены что хотите удалить ?')"  href="index.php?page=edit_admin_user&action=delete&id=<?=$row['admin_id'];?>" title="Удалить">
                                            <img src="<?=IMAGE_DIR;?>icons/cross.png"  alt="Delete"/>
                                        </a>
                                    <?}?>


                                </td>

                            </tr>
                            <?php


                        }
                    }
                }
                ?>
                </tbody>
            </table>

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



                var row_id         = $('#admin_user_id');          // set cookoe value equal to 0
                var row_name       = $('#admin_user_name');         // set cookoe value equal to 1
                var row_enable     = $('#admin_user_enable');        // set cookoe value equal to 2
                var row_date       = $('#admin_user_reg_date');        // set cookoe value equal to 3

                row_id.click(function(){
                    $.cookie('cookie_admin_user_sort_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_admin_user_sort_row', '1', { expires: 7 });
                });

                row_enable.click(function(){
                    $.cookie('cookie_admin_user_sort_row', '2', { expires: 7 });
                });

                row_date.click(function(){
                    $.cookie('cookie_admin_user_sort_row', '3', { expires: 7 });
                });





                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_admin_user_sort_row'),0]],
                    headers:{
                        4: {sorter: false }
                    }
                }); // sorter end

            });// ready end
        </script>
    </div>
</div>