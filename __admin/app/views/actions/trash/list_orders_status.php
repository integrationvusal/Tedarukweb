<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Статусы заказов</h3>
        <p style="text-align: right; margin-right: 20px;"><a href="index.php?page=add_orders_status"  class="add_order_status">+ Добавить статус</a></p>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">



        <?php $sfal = $pdo->query("SELECT * FROM `cs_orders_status` ORDER BY `id` ASC")->fetchAll(); ?>


        <?php

        $count = count($sfal);

        if( $count > 0 ){?>

        <table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
            <thead>
            <tr>
                <th id="order_status_id">ID</th>
                <th id="order_status_name">Название статуса</th>
                <th>Управление</th>
            </tr>
            </thead>
            <tbody>

            <?
            foreach( $sfal as $rfal ){?>
                <tr>
                    <td width="7%"><?php echo $rfal['id']; ?></td>
                    <td><?php echo $rfal['name_ru'];?></td>
                    <td width="18%">
                        <a href="index.php?page=edit_order_status&id=<?=$rfal['id']?>"><img src="<?=IMAGE_DIR;?>edit.png" class="list-ico" title="Редакотровать" /></a>

                        <!--<a onclick="return confirm('Вы уверены что хотите удалить ?')" href="index.php?page=delete_orders_status&id=<?=$rfal['id']?>" title="Удалить"><img src="<?=IMAGE_DIR;?>icons/cross.png" alt="Delete" /></a>-->

                    </td>
                </tr>
            <?php


            }



            }else{
                echo 'Данные отсуствует';
            }?>

            </tbody>
        </table>

        <div class="clear"></div>

        <script type="text/javascript">
            $(document).ready(function()
            {


                var row_id         = $('#order_status_id');           // set cookoe value equal to 0
                var row_name       = $('#order_status_name');         // set cookoe value equal to 1

                row_id.click(function(){
                    $.cookie('cookie_status_sort_row', '0', { expires: 7 });
                });

                row_name.click(function(){
                    $.cookie('cookie_status_sort_row', '1', { expires: 7 });
                });



                $("#myTable").tablesorter({
                    widgets: ["zebra", "filter"],
                    sortList: [[$.cookie('cookie_status_sort_row'),0]],
                    headers:{
                        2: {sorter: false }
                    }
                }); // sorter end

            }); // ready end
        </script>
    </div>
</div>