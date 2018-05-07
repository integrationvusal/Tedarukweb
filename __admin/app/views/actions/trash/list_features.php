<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Список свойств фильтров</h3>
        <p style="text-align: right; margin-right: 20px;"><a href="index.php?page=add_feature"  class="add_order_status">+ Добавить фильтр</a></p>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

        if (@$_GET['st'] == '') {
            $_GET['st'] = 1;
        }

        $query = $pdo->query("SELECT COUNT(`id`) FROM `cs_feature_value` ORDER BY `id`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));

        if ($setir > 0) {
        ?>
            <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                <thead>
                <tr>
                    <th id="news_list_id">ID</th>
                    <th id="news_list_name">Значение</th>
                    <th id="news_list_enable">Свойство</th>
                    <th>Управление</th>
                </tr>
                </thead>
                <tbody>
                <?php


                $link_sc = "index.php?page=list_features";

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

                        $sql = $pdo->query("
                            SELECT `cs_feature_value`.*, `s_features`.`name`
                            FROM `cs_feature_value`
                            LEFT JOIN `s_features` ON `s_features`.`id` = `cs_feature_value`.`feature_id`
                            LIMIT " . ($count * $j) . " ," . ($count) . "");

                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);


                        foreach( $row as $row ){ ?>
                            <tr>
                                <td width="5%"><?php echo $row['id']; ?></td>
                                <td><?php echo $row['value_ru']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td width="12%">
                                 <a href="index.php?page=edit_feature&id=<?php echo $row['id']; ?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать"/></a>

<a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=edit_feature&action=delete&id=<?php echo $row['id']; ?>" title="Удалить">
    <img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete"/>
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
            <!--<p>
                <li><a class="<?php if ($url == 'list_features') echo 'current' ?>" href="index.php?page=add_feature">Добавить</a></li>
            </p>-->

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

            echo '<div class="no_table_info">Информация отсутствует</div>';?>
            <p>
                <li><a class="<?php if ($url == 'list_features') echo 'current' ?>" href="index.php?page=add_feature">Добавить</a></li>
            </p>

       <?php }

        ?>


        <div class="clear"></div>


        <script type="text/javascript">
            $(document).ready(function()
            {
                var row_id       = $('#news_list_id');          // set cookoe value equal to 0
                var row_name     = $('#news_list_name');        // set cookoe value equal to 1
                var row_enable   = $('#news_list_enable'); // set cookoe value equal to 2
                var row_added    = $('#news_list_added');      // set cookoe value equal to 3

                row_id.click(function(){
                    $.cookie('cookie_news_sort_row', '0' , { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_news_sort_row', '1' , { expires: 7 });
                });

                row_enable.click(function(){
                    $.cookie('cookie_news_sort_row', '2' , { expires: 7 });
                });

                row_added.click(function(){
                    $.cookie('cookie_news_sort_row', '3' , { expires: 7 });
                });




                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_news_sort_row'),0]],
                    headers:{
                        3: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>
    </div>
</div>