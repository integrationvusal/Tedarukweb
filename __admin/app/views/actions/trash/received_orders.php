<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Принятые заказы</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">


        <?php



        $query=$pdo->query("SELECT * FROM `cs_orders` WHERE `status`=10");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));


        if($setir>0){

            ?>
            <table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
                <thead>

                <tr>
                    <th id="order_res_id">ID</th>
                    <th id="order_res_num">Номер заказа</th>
                    <th id="order_res_date">Дата поступления</th>
                    <th id="order_res_cost">Стоимость</th>
                    <th id="order_res_title">Метка</th>
                    <th>Управление</th>
                </tr>

                </thead>
                <tbody>

                <?php

                $count=30;
                $cnt=100;
                $rpp=30;
                $rad=1;

                $link_sc="index.php?page=list_delivery";

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

                        $row = $pdo->query("SELECT * FROM `cs_orders` WHERE `status`='10' OR `status`='8' ORDER BY `id` DESC LIMIT ".($count*$j)." ,".($count)."")->fetchAll();




                        // достаем метки
                        $row1 = $pdo->query("SELECT * FROM `cs_tags`")->fetchAll(PDO::FETCH_ASSOC);

                        foreach( $row as $row ) { ?>

                            <tr>
                                <td width="5%"><?php echo $row['id']; ?></td>
                                <td> <a href="index.php?page=view_order&id=<?=$row['id']?>" class="orders_link">Заказ N <?=$row['id']?></a></td>
                                <td><?=date("d.m.Y - H:i", strtotime($row['date'])); ?></td>
                                <td><?php echo $row['price']; echo ' '; echo $row['unit']; ?></td>

                                <td>
                                    <?php
                                    if( $row['tag_id'] != 0 )
                                    {
                                        foreach( $row1 as $row1_f )
                                        {
                                            if( $row1_f['id'] == $row['tag_id'] )
                                            {?>

                                                <div class="tag_wrapper">
                                                    <div class="tag_color" style="background-color: <?=$row1_f['color'];?>" title="<?=$row1_f['name'];?>" >

                                                    </div>
                                                </div>
                                            <?}
                                        }

                                    }
                                    ?>
                                </td>

                                <td width="12%">

                                    <!-- <a href="index.php?page=edit_delivery&id=<?php echo $row['id']; ?>">
                                        <img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" />
                                    </a>-->

                                    <a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=delete_order&id=<?php echo $row['id'];?>" title="Удалить">
                                        <img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" />
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

                if ($page>0){echo "<a href=\"$link_sc&st=1&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">« First</a><a href=\"$link_sc&st=".($page)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">« Previous</a>";}
                for ($i=$start; $i<$end; $i++){
                    if($i==$page){echo '<a href="#" class="number current">';}
                    else {echo "<a class='number' href=\"$link_sc&st=".($i+1)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">";}
                    echo ($i+1);

                    if($i==$page){echo "</b>";}else{echo "</a>";}
                    if ($i!=($end-1)) { echo ""; }
                }

                if($pages>$links&&$page<($pages-$rad-1)){echo " ... <a href=\"$link_sc&st=".($pages)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">".($pages)."</a>"; }
                if ($page<$pages-1){ echo " <a  href=\"$link_sc&st=".($page+2)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">Next »</a><a href=\"$link_sc&st=".($pages)."&order=".htmlspecialchars($_GET['order'])."&sort=".htmlspecialchars($_GET['sort'])."\">Last »</a>";}

                ?>
            </div>

        <?php

        }
        else {

            echo'<div class="no_table_info">Информация отсутствует</div>';

        }

        ?>

        <div class="clear"></div>


        <script type="text/javascript">
            $(document).ready(function()
            {




                var row_id         = $('#order_res_id');          // set cookoe value equal to 0
                var row_number     = $('#order_res_num');         // set cookoe value equal to 1
                var row_date       = $('#order_res_date');        // set cookoe value equal to 2
                var row_cost       = $('#order_res_cost');        // set cookoe value equal to 3
                var row_title      = $('#order_res_title');       // set cookoe value equal to 4

                row_id.click(function(){
                    $.cookie('cookie_order_res_sort_row', '0', { expires: 7 });
                });

                row_number.click(function(){
                    $.cookie('cookie_order_res_sort_row', '1', { expires: 7 });
                });

                row_date.click(function(){
                    $.cookie('cookie_order_res_sort_row', '2', { expires: 7 });
                });

                row_cost.click(function(){
                    $.cookie('cookie_order_res_sort_row', '3', { expires: 7 });
                });

                row_title.click(function(){
                    $.cookie('cookie_order_res_sort_row', '4', { expires: 7 });
                });




                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_order_res_sort_row'),0]],
                    headers:{
                        5: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>


    </div>
</div>