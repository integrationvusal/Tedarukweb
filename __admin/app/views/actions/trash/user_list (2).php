<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Список пользователей</h3>


        <!--searchbox start-->
          <div class="search_box">
              <form name="search_form" action="" method="POST">
                  <input type="text" name="search_query" placeholder="Поиск пользователей" <?php echo (!empty($_POST['search_query']))? ' value='.$_POST['search_query'] :''; ?> />
                  <input type="submit" name="search_user" value="Поиск" />
              </form>
          </div>
        <!--searchbox end-->



        <div class="clear"></div>

        <!--AJAX DATA LOADED HERE START-->

                <div class="user_addit_data"> </div>

        <!--AJAX DATA LOADED HERE START-->


    </div>
<div class="content-box-content">


<?php
if(  isset($_POST['save_new_resource'])  )
{
    array_pop($_POST);

    foreach( $_POST as $key => $key_val )
    {
        $stmt = $pdo->prepare("UPDATE `site_users` SET `group_id` = :group_id where `id`=:id");

        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':id', $id);

        $group_id = $key_val;
        $id = $key;

        $stmt->execute();

    }
}
?>


<?php

// SQL query construct start

$sql_string_select = "SELECT * FROM `cs_site_users`";

$sql_string_where = NULL;
if(  isset($_POST['search_query']) && (!empty($_POST['search_query']))  )
{
    $sql_string_where = " WHERE `name` LIKE ".$pdo->quote("%".trim(htmlspecialchars($_POST['search_query']))."%")." OR `surname` LIKE ".$pdo->quote("%".trim(htmlspecialchars($_POST['search_query']))."%")." ";
}
else
{

}

$sql_string_order = " ORDER BY `name` ASC";


$outut_sql_string = $sql_string_select . $sql_string_where . $sql_string_order;

// SQL query construct start


$sfal = $pdo->query( $outut_sql_string )->fetchAll();

$group_query = $pdo->query("SELECT * FROM `cs_user_group`")->fetchAll();

$setir = count($sfal);


if($setir>0){

    ?>
    <form name="" method="POST" action="">
    <table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
        <thead>
        <tr>
            <th id="user_id">ID</th>
            <th id="user_name">Имя</th>
            <th id="user_last_name">Фамилия</th>
            <th id="user_email">eMail</th>
            <th id="user_group">Группа</th>
            <th id="user_reg_date">Дата регистрации</th>
            <th>Управление</th>
        </tr>
        </thead>
        <tbody>
        <?php

        $count=50;
        $cnt=100;
        $rpp=50;
        $rad=1;

        $link_sc="index.php?page=user_list";

        if (isset($_GET['st']) and $_GET['st']>1){$page=$_GET['st']-1;}else{$page=0;}

        $links=$rad*2+1;
        $pages=ceil($setir/$rpp);
        $start=$page-$rad;

        if($start>$pages-$links){$start=$pages-$links;}
        if($start<0){$start=0;}
        $end=$start+$links;

        if ($end>$pages){ $end=$pages; }
        for ($j=$start; $j<$end; $j++){
            if($j==$page){

//SELECT * FROM `site_users` ORDER BY `id` DESC

                $sql=$pdo->query($outut_sql_string ." LIMIT ".($count*$j)." ,".($count)."")->fetchAll();
                 
                foreach($sql as $row ){
                    ?>
                    <tr>
                        <td width="5%"><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['surname']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <select name="<?php echo $row['id'];?>" class="group_selectors">

                                <option value="0">---</option>
                                <?php
                                foreach( $group_query as $variable )
                                {
                                    if( $variable['id'] == $row['group_id'] )
                                    {?>
                                        <option selected="selected" value="<?php echo $variable['id']; ?>"><?php echo $variable['gr_name']; ?></option>

                                    <?}
                                    else
                                    {?>
                                        <option value="<?php echo $variable['id']; ?>"><?php echo $variable['gr_name']; ?></option>
                                    <?}
                                }
                                ?>
                            </select>
                        </td>
                        <td><?=date("d.m.Y - H:i", strtotime($row['reg_date'])); ?></td>
                        <td>

                            <!--AJAX lock/unlock start-->

                            <div class="lock_unlock_user_wrapper" data="<?php echo $row['id']; ?>" title="lock/unlock user">

                                <div class="lock_unlock_user_container" >
                                    <div>off</div>
                                    <div>on</div>
                                </div>

                                <div class="lock_unlock_user_trigger <?php echo ($row['enable'])?' unlock':' lock';?>">
                                    <span>|||</span>
                                </div>

                            </div>

                            <!--AJAX lock/unlock  end-->

                            <div class="user_some_info">
                                &nbsp;&nbsp;<a href="index.php?page=get_more_info_ajax" data="<?=$row['id']?>" class="get_more_info" title="More info">?</a>
                                &nbsp;
                                <span class="loader"><img src='templates/default/images/2.gif'></span>
                            </div>


                        </td>


                    </tr>
                    <?php

                    if(empty($_GET['st'])){$_GET['st']=1;}

                if($row['id']==@$_GET['return']){

                    $pdo->query("UPDATE `cs_product` SET `enable`='1' WHERE `id`=".(int)$_GET['return']." ");

                    ?>
                    <script>
                        alert('Перемещено обратно.');
                        window.location="index.php?page=basket&st=<?php echo htmlspecialchars($_GET['st']); ?>";
                    </script>
                <?php
                }

                if($row['id']==@$_GET['delete']){

                    @$pdo->query("DELETE FROM `cs_product` WHERE `id`=".(int)$_GET['delete']." LIMIT 1");

                    ?>
                    <script>
                        alert('Удалено.');
                        window.location="index.php?page=basket&st=<?php echo $_GET['st']; ?>";
                    </script>
                <?php
                }

                } // while end (foreach end)

            }
        }
        ?>
        </tbody>
    </table>

    <script type="text/javascript" language="javascript">

        $(document).ready(function(){

            $('.lock_unlock_user_wrapper').click(function(){
                var id = $(this).attr('data');
                var trigger = $(this).parent().find('.lock_unlock_user_trigger');

                // alert( trigger.css('left'));
                var left_marg;
                if( trigger.css('left')=='0px' )
                {
                    //trigger.css('left','25px')
                    left_marg = '25px';
                    $(this).attr('user_available','off');
                }
                else
                {
                    //trigger.css('left','0px')
                    left_marg = '0px';
                    $(this).attr('user_available','on');
                }

                trigger.animate({'left':left_marg},80)


                // trigger.animate({'left':'25px'} , 100);

            });






            $('.get_more_info').click(function(e){
                $(this).parent().find('.loader').css('visibility','visible');
                e.preventDefault();
                var param = $(this).attr('data');

                $.ajax({
                    'url':'actions/pages/get_more_info_ajax.php',
                    'type':'POST',
                    'data':'id=' + param,
                    success: function(d){
                        $('.loader').css('visibility','hidden');
                        $('.user_addit_data')
                            .css('display','block')
                            .html(d);
                    }

                });
            }); // click end first AJAX query


            $('.lock_unlock_user_wrapper').click(function(){
                $(this).parent().find('.loader').css('visibility','visible');

                var param1 = $(this).attr('data');
                var user_lock = $(this).attr('user_available');

                $.ajax({
                    'url':'actions/pages/get_more_info_ajax.php',
                    'type':'POST',
                    'data':'user_id=' + param1 + '&' + 'user_lock=' + user_lock,
                    success: function(success){
                        $('.loader').css('visibility','hidden');
                    }

                });

            });// click end second AJAX query







            // AJAX query for group_selectors

           $('.group_selectors').change(function(){

             //$(this).parent().find('.loader').css('visibility','visible');
             $(this).parent().parent().find('.loader').css('visibility','visible');

             var group_id = $(this).val();
             var user_id = $(this).attr('name');


               $.ajax({
                   'url':'actions/pages/get_more_info_ajax.php',
                   'type':'POST',
                   'data':'user_id=' + user_id + '&' + 'group_id=' + group_id,
                   success: function(success){
                       $('.loader').css('visibility','hidden');

                   }
               }); // ajax query end


           }); // change handle end




            //$('.user_addit_data');

            var win_width = $(window).width();
            var win_height = $(window).height();

            //alert(win_width + ' ' + win_height);


            var pop_up_win =  $('.user_addit_data');

            var height_pop_up = pop_up_win.height();
            var width_pop_up = pop_up_win.width();

            //alert(height_pop_up + ' ' + width_pop_up);

            var x_position = (win_width - width_pop_up) / 2;
            var y_position = (win_height - height_pop_up) / 2;

            pop_up_win.css({  'top':(y_position - 150)+'px' , 'left':x_position +'px'  });

            //alert(y_position);

            //var x_ax = (  parseInt(win_width)  '')/2;


        });// ready end




    </script>

   <!--<p><input class="button" type="submit" name="save_new_resource" value="Сохранить" id="user_list_save_btn"></p>-->

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

}
else {

    echo'<div class="no_table_info">Информация отсутствует</div>';

}

?>

<div class="clear"></div>

<script type="text/javascript">
    $(document).ready(function()
    {


        var row_id         = $('#user_id');          // set cookoe value equal to 0
        var row_name       = $('#user_name');        // set cookoe value equal to 1
        var row_surname    = $('#user_last_name');   // set cookoe value equal to 2
        var row_email      = $('#user_email');       // set cookoe value equal to 3
        var row_group      = $('#user_group');       // set cookoe value equal to 4
        var row_reg_date   = $('#user_reg_date');    // set cookoe value equal to 5

        row_id.click(function(){
            $.cookie('cookie_user_sort_row', '0', { expires: 7 });
        });

        row_name.click(function(){
            $.cookie('cookie_user_sort_row', '1', { expires: 7 });
        });

        row_surname.click(function(){
            $.cookie('cookie_user_sort_row', '2', { expires: 7 });
        });

        row_email.click(function(){
            $.cookie('cookie_user_sort_row', '3', { expires: 7 });
        });

        row_group.click(function(){
            $.cookie('cookie_user_sort_row', '4', { expires: 7 });
        });

        row_reg_date.click(function(){
            $.cookie('cookie_user_sort_row', '4', { expires: 7 });
        });






        $("#myTable").tablesorter({
            widgets: ["zebra", "filter"],
            sortList: [[$.cookie('cookie_user_sort_row'),0]],
            headers:{
                6: {sorter: false }
            }
        }); // sorter end

    }); // ready end
</script>
</div>
</div>