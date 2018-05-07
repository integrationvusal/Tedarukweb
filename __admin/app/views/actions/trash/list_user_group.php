<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Скидки</h3>
        <p style="text-align: right; margin-right: 20px;"><a href="index.php?page=add_user_group"  class="add_order_status">+ Добавить скидку</a></p>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

        if (@$_GET['st'] == '') {
            $_GET['st'] = 1;
        }

        // $name_ord_sel = (@$_GET['order'] == 'ASC') ? 'DESC' : 'ASC';
        // $name_sort_sel = (@$_GET['sort'] == 'name') ? 'content_pagetitle_' . DEFAULT_LANG_DIR : 'content_under_menu';

        $query = $pdo->query("SELECT * FROM `cs_user_group` ORDER BY `id`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));

        if ($setir > 0) {


            ?>
            <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th id="user_gr_id">ID</th>
                    <th id="user_gr_name">Название</th>
                    <th id="user_gr_per">Процент</th>
                    <th id="user_gr_date">Добавлено</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                <?php


                $link_sc = "index.php?page=list_user_group";

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


                        $sql = $pdo->query('SELECT * FROM `cs_user_group` LIMIT '. ($count * $j) . " ," . ($count) .'');

                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);


                        foreach( $row as $row ){

                            //$block = ($row['enable'] == '1') ? '<span style="color: green;">Доступно</span>' : '<span style="color: red;">Скрытый</span>';



                            ?>
                            <tr>


                                <td width="5%"><?=$row['id']; ?></td>
                                <td><?=$row['gr_name']; ?></td>
                                <td><?=$row['gr_discount']; ?></td>
                                <td><?=date("d.m.Y - H:i", strtotime($row['gr_ins_date'])); ?></td>
                                <td width="12%">
                                    <a href="index.php?page=edit_group&action=edit&id=<?php echo $row['id']; ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/></a>

                                    <a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=edit_group&action=delete&id=<?php echo $row['id']; ?>" title="Удалить">
                                        <img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete"/>
                                    </a>
                                </td>


                            </tr>
                        <?php


                        } // foreach end
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


                var row_id         = $('#user_gr_id');           // set cookie value equal to 0
                var row_name       = $('#user_gr_name');         // set cookie value equal to 1
                var row_per        = $('#user_gr_per');          // set cookie value equal to 2
                var row_date       = $('#user_gr_date');         // set cookie value equal to 3

                row_id.click(function(){
                    $.cookie('cookie_user_gr_sort_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_user_gr_sort_row', '1', { expires: 7 });
                });

                row_per.click(function(){
                    $.cookie('cookie_user_gr_sort_row', '2', { expires: 7 });
                });

                row_date.click(function(){
                    $.cookie('cookie_user_gr_sort_row', '3', { expires: 7 });
                });





                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_user_gr_sort_row'),0]],
                    headers:{
                        4: {sorter: false }
                    }
                }); // sorter end

            }); //ready end
        </script>
    </div>
</div>