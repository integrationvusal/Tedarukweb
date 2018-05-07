<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Список брендов</h3>

        <p style="display: inline-block; float: right; margin-right: 20px;"><a href="index.php?page=add_brend" class="add_order_status">+ Добавить бренд</a></p>



        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск брендов" value="<?=@$_POST['search_query'];?>" />
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

        $query = $pdo->query("SELECT * FROM `cs_brend_list`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));



        if ($setir > 0) {


            ?>



            <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th id="brend_id">ID</th>
                    <th id="brend_name">Название</th>
                    <th id="brend_add_date">Дата добавления</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                <?php


                $link_sc = "index.php?page=list_brend";

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

                        $sql = $pdo->query("SELECT * FROM `cs_brend_list`  LIMIT " . ($count * $j) . " ," . ($count) . "");


                        // Search code start
                        if( isset($_POST['search_user']) && !empty($_POST['search_query']) )
                        {

                            $str = htmlspecialchars($_POST['search_query']);      // Query string
                            $search_key_arr = array();                            // Empty array
                            $table_row_arr = array();                             // array for table rows
                            $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces



                            // table rows
                            $table_row_arr[] = 'brend_name';

                            $sql_part1 = " WHERE `".$table_row_arr[0]."` ";

                            foreach( $search_key_arr_explode as $key=>$value )
                            {
                                if( $key == 0 )
                                {
                                    $sql_part1 .= "LIKE ".$pdo->quote("%". $value."%")."";
                                }
                                else
                                {
                                    $sql_part1 .= " OR `".$table_row_arr[0]."` LIKE ".$pdo->quote("%". $value."%")."";
                                }

                            }

                            $sql = $pdo->query("SELECT * FROM `cs_brend_list` ".$sql_part1);

                        }

                        else
                        {

                        }
                        // search code end





                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);

                        foreach( $row as $row ){

                            //$block = ($row['admin_block'] == 'no') ? '<span style="color: green;">Доступно</span>' : '<span style="color: red;">Блокирован</span>';


                            ?>
                            <tr>
                                <td width="5%"><?php echo $row['brend_id']; ?></td>
                                <td><?php echo $row['brend_name']; ?></td>
                                <td><?php echo date("d.m.Y - H:i", strtotime($row['brend_ins_date'])); ?></td>

                                <td width="12%">
                                    <a href="index.php?page=edit_brend&id=<?php echo $row['brend_id'];?>">
                                        <img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/>
                                    </a>

                                    <a onclick="return confirm('Вы уверены что хотите удалить ?')"  href="index.php?page=edit_brend&action=delete&id=<?php echo $row['brend_id'];?>" title="Удалить">
                                        <img src="<?=IMAGE_DIR;?>icons/cross.png"  alt="Delete"/>
                                    </a>
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



                var row_id         = $('#brend_id');             // set cookoe value equal to 0
                var row_name       = $('#brend_name');           // set cookoe value equal to 1
                var row_date       = $('#brend_add_date');       // set cookoe value equal to 3

                row_id.click(function(){
                    $.cookie('cookie_brend_sort_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_brend_sort_row', '1', { expires: 7 });
                });

                row_date.click(function(){
                    $.cookie('cookie_brend_sort_row', '2', { expires: 7 });
                });


                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_brend_sort_row'),0]],
                    headers:{
                        3: {sorter: false }  // Блокируем 4 столбец
                    }
                }); // sorter end

            });// ready end
        </script>
    </div>
</div>