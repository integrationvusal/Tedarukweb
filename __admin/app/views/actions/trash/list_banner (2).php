<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">

    <div class="content-box-header">
        <h3>Список баннеров</h3>

        <!--searchbox start-->
        <div class="search_box">
            <form name="search_form" action="" method="POST">
                <input type="text" name="search_query" placeholder="Поиск баннеров" value="<?=@$_POST['search_query'];?>" />
                <input type="submit" name="search_user" value="Поиск" />
            </form>
        </div>
        <!--searchbox end-->

        <div class="clear"></div>
    </div>

    <div class="content-box-content">

        <?php
        $positions_arr = array(
            1 => "Слайдер( на главной стр.)",
            2 => "Боковой баннер",
            3 => "Баннер 730х70",
            4 => "Баннер 225х300",
            5 => "Слайдер( на внутр. стр.)",
            6 => "Баннер (730х70) 2",
        );

        $query = $pdo->query("SELECT * FROM `cs_banner`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));


        if($setir>0){

            ?>
            <table width="100%" cellpadding="0" cellspacing="0"  id="myTable" class="tablesorter">
                <thead>

                <tr>
                    <th id="banner_list_id">ID</th>
                    <th id="banner_list_name">Название</th>
                    <th id="banner_list_pos">Позиция</th>
                    <th id="banner_list_type">Тип</th>
                    <th id="banner_list_add_date">Дата добавления</th>
                    <th>Управление</th>
                </tr>

                </thead>
                <tbody>

                <?php

                $count=10;
                $cnt=100;
                $rpp=10;
                $rad=1;

                $link_sc="index.php?page=list_banner";

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

                       // $row = $pdo->query("SELECT * FROM `cs_banner` ORDER BY `id` DESC LIMIT ".($count*$j)." ,".($count)."")->fetchAll();

                        $sql = $pdo->query("SELECT * FROM `cs_banner` ORDER BY `id` DESC  LIMIT ".($count*$j)." ,".($count)."");



                        // Search code start
                        if( isset($_POST['search_user']) && !empty($_POST['search_query']) )
                        {

                            $str = htmlspecialchars($_POST['search_query']);      // Query string
                            $search_key_arr = array();                            // Empty array
                            $table_row_arr = array();                             // array for table rows
                            $search_key_arr_explode = explode(' ' ,trim($str));   // Exploding search query by spaces



                            // table rows
                            $table_row_arr[] = 'name';

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

                            $sql = $pdo->query("SELECT * FROM `cs_banner` ".$sql_part1);

                        }

                        else
                        {

                        }
                        // search code end




                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);



                        foreach( $row as $row ) { ?>

                            <tr>
                                <td width="5%"><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td><?php echo $positions_arr[$row['position']]; ?></td>
                                <td><?php echo $row['type']; ?></td>
                                <td><?=date("d.m.Y - H:i", strtotime($row['added_date'])); ?></td>

                                <td width="12%">

                                    <a href="index.php?page=edit_banner&id=<?php echo $row['id']; ?>">
                                        <img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" />
                                    </a>

                                    <a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=edit_banner&action=delete&id=<?php echo $row['id'];?>" title="Удалить">
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


/*
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
*/
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

        }
        else {

            echo'<div class="no_table_info">Информация отсутствует</div>';

        }

        ?>

        <div class="clear"></div>

        <script type="text/javascript">
            $(document).ready(function()
            {



                var row_id       = $('#banner_list_id');          // set cookoe value equal to 0
                var row_name     = $('#banner_list_name');        // set cookoe value equal to 1
                var row_pos      = $('#banner_list_pos');         // set cookoe value equal to 2
                var row_type     = $('#banner_list_type');        // set cookoe value equal to 3
                var row_added    = $('#banner_list_add_date');    // set cookoe value equal to 4

                row_id.click(function(){
                    $.cookie('cookie_menu_banner_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_banner_row', '1', { expires: 7 });
                });

                row_pos.click(function(){
                    $.cookie('cookie_banner_row', '2', { expires: 7 });
                });

                row_type.click(function(){
                    $.cookie('cookie_banner_row', '3', { expires: 7 });
                });

                row_added.click(function(){
                    $.cookie('cookie_banner_row', '4', { expires: 7 });
                });








                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_banner_row'),0]],
                    headers:{
                        5: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>

    </div>
</div>