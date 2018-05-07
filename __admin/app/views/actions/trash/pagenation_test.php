<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Pagenation test</h3>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

        $stmt = $pdo->query("SELECT * FROM `cs_pagenation_test`");
        $setir = $stmt->rowCount();


        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($setir>0){

            ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th
                </tr>
                </thead>
                <tbody>
                <?php
                /**
                 * Settings for pagenation
                 * @count - количество записей на одной странице
                 * @rpp - тоже самое
                 *
                */
                $count=10;
                $cnt=100;
                $rpp=10;
                $rad=1;

                $link_sc="index.php?page=pagenation_test";

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

                       // $sql = mysql_query("SELECT * FROM `cs_pagenation_test` WHERE `content_delete`='no' AND `content_on_page`=0 AND `content_show_on_menu`='no' ORDER BY `content_id` DESC LIMIT ".($count*$j)." ,".($count)."");

                        $sql = $pdo->query("SELECT * FROM `cs_pagenation_test`  ORDER BY `id` DESC LIMIT ".($count*$j)." ,".($count)."");

                        $row = $sql->fetchAll(PDO::FETCH_ASSOC);


                       // while($row = mysql_fetch_assoc($sql)){

                        foreach( $row as $row ){

                           // $block=($row['content_hide_page']=='no')?'<span style="color: green;">Доступно</span>':'<span style="color: red;">Скрытый</span>';

                            if(@$_GET['st']==''){$_GET['st']=1;}

                            ?>
                            <tr>
                                <td  id="id" width="5%"><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                            </tr>
                            <?php


                        } // while end
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

        }
        else {

            echo'<div class="no_table_info">Информация отсутствует</div>';

        }

        ?>

        <div class="clear"></div>
    </div>
</div>