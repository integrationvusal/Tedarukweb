<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Список меток</h3>
        <p style="text-align: right; margin-right: 20px;"><a href="index.php?page=add_tag"  class="add_order_status">+ Добавить метку</a></p>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <?php

        /* if(@$_GET['st']==''){$_GET['st']=1;}

          $name_ord_sel=( @$_GET['order']=='ASC' )?'DESC':'ASC';
          //$name_sort_sel=( @$_GET['sort']=='name' )?'content_pagetitle_'.DEFAULT_LANG_DIR:'content_under_menu';
        */

        $query=$pdo->query("SELECT * FROM `cs_tags`");
        $setir = count($query->fetchAll(PDO::FETCH_ASSOC));


        if($setir>0){

            ?>
            <table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter" >
                <thead>

                <tr>
                    <th id="tag_id">ID</th>
                    <th id="tag_name">Название</th>
                    <th id="tag_color">Цвет</th>
                    <th>Управление</th>
                </tr>

                </thead>
                <tbody>

                <?php

                $count=30;
                $cnt=100;
                $rpp=30;
                $rad=1;

                $link_sc="index.php?page=list_tags";

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

                        $row = $pdo->query("SELECT * FROM `cs_tags` ORDER BY `id` DESC LIMIT ".($count*$j)." ,".($count)."")->fetchAll(PDO::FETCH_ASSOC);



                        foreach( $row as $row ) { ?>

                            <tr>
                                <td width="5%"><?php echo $row['id']; ?></td>
                                <td><?php echo $row['name']; ?></td>
                                <td>

                                    <div class="tag_wrapper">
                                        <div class="tag_color" style="background-color: <?php echo $row['color']; ?>" title="<?php echo $row['color']; ?>" >

                                        </div>
                                    </div>
                                </td>

                                <td width="12%">

                                    <a href="index.php?page=edit_tag&id=<?php echo $row['id']; ?>">
                                        <img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" />
                                    </a>

                                    <a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=edit_tag&action=delete&id=<?php echo $row['id'];?>" title="Удалить">
                                        <img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" />
                                    </a>

                                </td>
                            </tr>


                            <?php

                        if($row['id']==@$_GET['delete']){

                            $pdo->query("UPDATE `cs_publish` SET `content_delete`='yes' WHERE `id`=".(int)$_GET['delete']." LIMIT 1");
                            ?>
                            <script>
                                alert('Удалено.');
                                window.location="index.php?page=news&st=<?php echo $_GET['st']; ?>";
                            </script>
                        <?php
                        }

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


                var row_id           = $('#tag_id');             // set cookie value equal to 0
                var row_name         = $('#tag_name');           // set cookie value equal to 1
                var row_color        = $('#tag_color');          // set cookie value equal to 2

                row_id.click(function(){
                    $.cookie('cookie_tag_sort_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_tag_sort_row', '1', { expires: 7 });
                });

                row_color.click(function(){
                    $.cookie('cookie_tag_sort_row', '2', { expires: 7 });
                });






                $("#myTable").tablesorter({
                    widgets: ['zebra'],
                    sortList: [[$.cookie('cookie_tag_sort_row'),0]],
                    headers:{
                         3: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>
    </div>
</div>