<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

?>

<div class="content-box">
<div class="content-box-header">
    <h3>Добавить баннер</h3>
    <div class="clear"></div>
</div>



<div class="content-box-content">

<?php



if( isset($_GET['id']) && !empty($_GET['id']) )
{
    $id = (int)$_GET['id'];

}
else
{
    echo 'Неправильное обращение к скрипту';
    die();
}

if(isset( $_GET['action']) && ($_GET['action'] == 'delete') )
{
    $del_res = $pdo->exec("DELETE FROM `cs_banner` WHERE `id`=".$pdo->quote($id)." ");
    if($del_res)
    {
        echo '<script language="javascript" type="text/javascript"> window.location = "index.php?page=list_banner"; </script>';
    }
}


$cur_ban_r = $pdo->query("SELECT * FROM `cs_banner` WHERE `id`=".$pdo->quote($id)."")->fetch(PDO::FETCH_ASSOC);

$category_has_banner = $pdo->query("SELECT `related_cat_id` FROM `cs_banner`")->fetchAll();

?>

<!--Banner preview start-->
 <div class="banner_preview">

     <?php
      if( ($cur_ban_r['type']=='photo') && ($cur_ban_r['position'] != 2) )
      {
        $src = "../uploads/banners/".$cur_ban_r['content_b'];
        echo '<img src="'.$src.'" />';
      }
      if( ($cur_ban_r['type']=='photo') && ($cur_ban_r['position'] == 2) )
      {
          $src1 = "../uploads/banners/".$cur_ban_r['content_1280'];
          $src2 = "../uploads/banners/".$cur_ban_r['content_1366'];
          $src3 = "../uploads/banners/".$cur_ban_r['content_1600'];
          $src4 = "../uploads/banners/".$cur_ban_r['content_1920'];

          echo '<img src="'.$src1.'" /><br /><br />';
          echo '<img src="'.$src2.'" /><br /><br />';
          echo '<img src="'.$src3.'" /><br /><br />';
          echo '<img src="'.$src4.'" /><br /><br />';
      }
     ?>

 </div>
<!--Banner preview   end-->



<?php



if( isset($_POST['save']) )
{



    $today = date('Y-m-d h:i:s');




    if( $_POST['position'] != 2)
    {
      if( $_FILES['uploaded_banner']['name'] =='' )
      {
          // Если при редактировании не обновили фото
          $content_b = $cur_ban_r['content_b'];

      }
        else
      {
          

          if ((($_FILES["uploaded_banner"]["type"] == "image/gif")
              || ($_FILES["uploaded_banner"]["type"] == "image/jpg")
              || ($_FILES["uploaded_banner"]["type"] == "image/jpeg")
              || ($_FILES["uploaded_banner"]["type"] == "application/x-shockwave-flash")
              || ($_FILES["uploaded_banner"]["type"] == "image/png")))
          {
              if ($_FILES["uploaded_banner"]["error"] > 0)
              {
                  echo "Error Code: " . $_FILES["uploaded_banner"]["error"] . "<br>";
              }
              else
              {

                  $content_1920 = date('Y_m_d_h_i_s').'_'.$_FILES["uploaded_banner"]["name"];

                  move_uploaded_file($_FILES["uploaded_banner"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/uploads/banners/".$content_1920);


              }
          }
          else
          {
              echo "Invalid file single img";
          }
      } // if-else end (обновлена-ли фотография)



        // write to db
        $stmt = $pdo->prepare("UPDATE `cs_banner` SET `name` =:ban_name , `link` =:link , `type` =:type ,`position` =:position ,`added_date` =:added_date ,`content_b` =:content_b , `content_b`=:banner_url , `related_cat_id`=:related_cat_id  WHERE `id`=".$pdo->quote($id)."");


        $stmt->bindParam(':ban_name', $ban_name);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':added_date', $added_date);
        $stmt->bindParam(':content_b', $content_b);
        $stmt->bindParam(':banner_url', $banner_url);
        $stmt->bindParam(':related_cat_id', $related_cat_id);


        $ban_name = htmlspecialchars($_POST['ban_name']);
        $link = htmlspecialchars($_POST['url']);
        $type = htmlspecialchars($_POST['type']);
        $position = htmlspecialchars($_POST['position']);
        $added_date = $today;
        $content_b = $content_b;
        $banner_url = (!empty($content_1920)) ? $content_1920 : $cur_ban_r['content_b'];
        $related_cat_id = $_POST['related_cat_id'];

        $stmt->execute();

        echo '<script language="javascript" type="text/javascript"> window.location = "index.php?page=list_banner"; </script>';

    }






    if($_POST['position'] == 2)
    {

      if( $_FILES['uploaded_banner_1280']['name'] =='' )
      {
          // Если при редактировании не обновили фото
          $content_1280 = $cur_ban_r['content_1280'];
          $content_1366 = $cur_ban_r['content_1366'];
          $content_1600 = $cur_ban_r['content_1600'];
          $content_1920 = $cur_ban_r['content_1920'];
          $content_b = '';
      }
      else
      {
          // Если при редактировании обновили фото

          /**
          1280
           */
          if ((($_FILES["uploaded_banner_1280"]["type"] == "image/gif")
              || ($_FILES["uploaded_banner_1280"]["type"] == "image/jpg")
              || ($_FILES["uploaded_banner_1280"]["type"] == "image/jpeg")
              || ($_FILES["uploaded_banner_1280"]["type"] == "application/x-shockwave-flash")
              || ($_FILES["uploaded_banner_1280"]["type"] == "image/png")))
          {

              if ($_FILES["uploaded_banner_1280"]["error"] > 0)
              {
                  echo "Error Code: " . $_FILES["uploaded_banner_1280"]["error"] . "<br>";
              }
              else
              {

                  $content_1280 = date('Y_m_d_h_i_s').'_'.$_FILES["uploaded_banner_1280"]["name"];

                  move_uploaded_file($_FILES["uploaded_banner_1280"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/uploads/banners/".$content_1280);


              }
          }
          else
          {
              echo "Invalid file 1280";

          }



          /**
          1366
           */
          if ((($_FILES["uploaded_banner_1366"]["type"] == "image/gif")
              || ($_FILES["uploaded_banner_1366"]["type"] == "image/jpg")
              || ($_FILES["uploaded_banner_1366"]["type"] == "image/jpeg")
              || ($_FILES["uploaded_banner_1366"]["type"] == "application/x-shockwave-flash")
              || ($_FILES["uploaded_banner_1366"]["type"] == "image/png")))
          {
              if ($_FILES["uploaded_banner_1366"]["error"] > 0)
              {
                  echo "Error Code: " . $_FILES["uploaded_banner_1366"]["error"] . "<br>";
              }
              else
              {

                  $content_1366 = date('Y_m_d_h_i_s').'_'.$_FILES["uploaded_banner_1366"]["name"];

                  move_uploaded_file($_FILES["uploaded_banner_1366"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/uploads/banners/".$content_1366);


              }
          }
          else
          {
              echo "Invalid file 1366";

          }


          /**
          1600
           */

          if ((($_FILES["uploaded_banner_1600"]["type"] == "image/gif")
              || ($_FILES["uploaded_banner_1600"]["type"] == "image/jpg")
              || ($_FILES["uploaded_banner_1600"]["type"] == "image/jpeg")
              || ($_FILES["uploaded_banner_1600"]["type"] == "application/x-shockwave-flash")
              || ($_FILES["uploaded_banner_1600"]["type"] == "image/png")))

          {
              if ($_FILES["uploaded_banner_1600"]["error"] > 0)
              {
                  echo "Error Code: " . $_FILES["uploaded_banner_1600"]["error"] . "<br>";
              }
              else
              {

                  $content_1600 = date('Y_m_d_h_i_s').'_'.$_FILES["uploaded_banner_1600"]["name"];

                  move_uploaded_file($_FILES["uploaded_banner_1600"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/uploads/banners/".$content_1600);


              }

          }
          else
          {
              echo "Invalid file 1600";

          }

          /**
          1920
           */

          if ((($_FILES["uploaded_banner_1920"]["type"] == "image/gif")
              || ($_FILES["uploaded_banner_1920"]["type"] == "image/jpg")
              || ($_FILES["uploaded_banner_1920"]["type"] == "image/jpeg")
              || ($_FILES["uploaded_banner_1920"]["type"] == "application/x-shockwave-flash")
              || ($_FILES["uploaded_banner_1920"]["type"] == "image/png")))
          {
              if ($_FILES["uploaded_banner_1920"]["error"] > 0)
              {
                  echo "Error Code: " . $_FILES["uploaded_banner_1920"]["error"] . "<br>";
              }
              else
              {

                  $content_1920 = date('Y_m_d_h_i_s').'_'.$_FILES["uploaded_banner_1920"]["name"];

                  move_uploaded_file($_FILES["uploaded_banner_1920"]["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/uploads/banners/".$content_1920);


              }

          }
          else
          {
              echo "Invalid file 1920";

          }
      }




        // write to db
        $stmt = $pdo->prepare("UPDATE  `cs_banner`
         SET
          `name` = :ban_name ,
          `link` = :link ,
          `type` = :type ,
          `position` = :position ,
          `added_date` = :added_date ,
          `content_1280` = :content_1280 ,
          `content_1366` = :content_1366 ,
          `content_1600` = :content_1600 ,
          `content_1920` = :content_1920 ,
          `body_background` = :body_background ,
          `content_b` =:content_b,
           `related_cat_id`=:related_cat_id

           WHERE `id`=".$id."");

        // $stmt->bindParam(':foto_url', $foto_url);
        $stmt->bindParam(':ban_name', $ban_name);
        $stmt->bindParam(':link', $link);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':position', $position);
        $stmt->bindParam(':added_date', $added_date);
        $stmt->bindParam(':content_1280', $content_1280);
        $stmt->bindParam(':content_1366', $content_1366);
        $stmt->bindParam(':content_1600', $content_1600);
        $stmt->bindParam(':content_1920', $content_1920);
        $stmt->bindParam(':body_background', $body_background);
        $stmt->bindParam(':content_b', $content_b);
        $stmt->bindParam(':related_cat_id', $related_cat_id);



        $ban_name = htmlspecialchars($_POST['ban_name']);
        $link = htmlspecialchars($_POST['url']);
        $type = htmlspecialchars($_POST['type']);
        $position = htmlspecialchars($_POST['position']);
        $added_date = $today;
        $content_1280 = (!empty($content_1280)) ? $content_1280 : $cur_ban_r['content_1280'];
        $content_1366 = (!empty($content_1366)) ? $content_1366 : $cur_ban_r['content_1366'];
        $content_1600 = (!empty($content_1600)) ? $content_1600 : $cur_ban_r['content_1600'];
        $content_1920 = (!empty($content_1920)) ? $content_1920 : $cur_ban_r['content_1920'];;
        $body_background = htmlspecialchars($_POST['body_background']);
        $content_b = $content_b;
        $related_cat_id = $_POST['related_cat_id'];

        $stmt->execute();


        echo '<script language="javascript" type="text/javascript"> window.location = "index.php?page=list_banner"; </script>';




    }
    echo "<script language='javascript' type='text/javascript'>window.location=index.php?page=list_banner</script>";
}// submit button pressed end
?>

<form name="add_banner" action="" method="POST" enctype="multipart/form-data">

    <p>
        <label>Название баннера</label>
        <input type="text" name="ban_name" class="text-input small-input" value="<?=$cur_ban_r['name']?>" />
    </p>

    <p>
        <label>Тип баннера</label>
        <select name="type">
            <option value="photo" <?php echo ($cur_ban_r['type'] == 'photo')?'selected="selected"':'';  ?> >Фото</option>
            <option value="flash" <?php echo ($cur_ban_r['type'] == 'flash')?'selected="selected"':'';  ?> >Flash</option>
        </select>
    </p>

    <p>
        <label>Ссылка &nbsp; (пример : http://google.ru)</label>
        <input type="text" name="url" class="text-input small-input" value="<?=$cur_ban_r['link']?>"/>
    </p>

    <p>
        <label>Местоположение</label>
        <select name="position">
            <option value="1" <?php echo ($cur_ban_r['position'] == '1')?'selected="selected"':'';  ?> >Слайдер( на главной стр.)</option>
            <option value="5" <?php echo ($cur_ban_r['position'] == '5')?'selected="selected"':'';  ?> >Слайдер( на внутр. стр.)</option>
            <option value="2" <?php echo ($cur_ban_r['position'] == '2')?'selected="selected"':'';  ?> >Боковой баннер</option>
            <option value="3" <?php echo ($cur_ban_r['position'] == '3')?'selected="selected"':'';  ?> >Баннер 730х70</option>
            <option value="4" <?php echo ($cur_ban_r['position'] == '4')?'selected="selected"':'';  ?> >Баннер 225х300</option>
        </select>
    </p>

    <p class="file_upload_container">
        <label>Выбрать файл ( размер <span class="rezolution"></span> )</label>
        <input type="file" name="uploaded_banner" />
    </p>





    <!--Only for "боковой баннер" start-->

    <?php  if($cur_ban_r['position'] == 2){?>

        <p>
            <label>Баннер относится к категории : <span class="rezolution">
                    <?php
                    if( !empty($cur_ban_r['related_cat_id']) )
                        {
                            $ban_cat = $pdo->query("SELECT `content_pagetitle_ru` FROM `cs_content_list` WHERE `content_id`=".$pdo->quote($cur_ban_r['related_cat_id'])."")->fetch(PDO::FETCH_ASSOC);
                            echo $ban_cat['content_pagetitle_ru'];
                        }
                    ?>
            </span></label>
        </p>

        <p>
            <label>Выбрать категорию</label>
            <select name="related_cat_id" class="small-input">
                <option value="0"></option>
                <?php

                $categories= $pdo->query("SELECT `content_id` , `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "` FROM `cs_content_list` WHERE `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=0 ORDER BY `content_id` DESC")->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $item):
                    ?>
                    <option <? if( $cur_ban_r['related_cat_id'] == $item['content_id']  ){ echo 'selected="selected"';} ?>   <?php foreach( $category_has_banner as $item1 ){  echo ( @$item1['related_cat_id'] == $item['content_id'] )? 'class="has_banner"' : '' ; } ?> value="<?php echo $item['content_id']; ?>"><?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></option>

                    <?php
                    $sub_categories= $pdo->query("
                                SELECT
                                    `content_id` , `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "`
                                FROM
                                    `cs_content_list`
                                WHERE
                                    `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=" . (int)$item['content_id'] . "
                                ORDER BY
                                    `content_id` DESC
                            ")->fetchAll(PDO::FETCH_ASSOC);



                    if (!empty($sub_categories)):
                        foreach ($sub_categories as $item):

                            ?>
                            <option <? if( $cur_ban_r['related_cat_id'] == $item['content_id']  ){ echo 'selected="selected"';} ?> <?php foreach( $category_has_banner as $item1 ){  echo ( @$item1['related_cat_id'] == $item['content_id'] )? 'class="has_banner"' : '' ; } ?>  value="<?php echo $item['content_id']; ?>" >-- <?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></option>

                            <?php
                            $sub_categories_child= $pdo->query("
                                            SELECT
                                                `content_id`, `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "`
                                            FROM
                                                `cs_content_list`
                                            WHERE
                                                `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=" . (int)$item['content_id'] . "
                                            ORDER BY
                                                `content_id` DESC
                                        ")->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($sub_categories_child)):
                                foreach ($sub_categories_child as $item):
                                    ?>
                                    <option <? if( $cur_ban_r['related_cat_id'] == $item['content_id']  ){ echo 'selected="selected"';} ?> <?php foreach( $category_has_banner as $item1 ){  echo ( @$item1['related_cat_id'] == $item['content_id'] )? 'class="has_banner"' : '' ; } ?> value="<?php echo $item['content_id']; ?>">---- <?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></option>
                                <?php endforeach ?>
                            <?php endif ?>

                        <?php endforeach ?>
                    <?php endif ?>

                <?php endforeach ?>



            </select>
        <p class="warning"></p>

            <script type="text/javascript" language="javascript">
                $(document).ready(function(){
                    $('.has_banner').css({'backgroundColor':'#EA5347' , 'color':'#ffffff'});
                });
            </script>
        </p>

    <?}?>
    <!--Only for "боковой баннер" end-->




    <p>
        <input type="submit" name="save" value="Сохранить" class="button" />
    </p>



</form>

<script language="javascript" type="text/javascript">
    $(document).ready(function(){

        var position = $('select[name=position]');

        var file_upload_container = $('.file_upload_container');

        var file_input = $('<label>Выбрать файл ( размер <span class="rezolution"></span> )</label><input type="file" name="uploaded_banner" />');

        var many_input = $('<p><label>Цвет фона</label><input type="text" name="body_background" value="<?php echo $cur_ban_r['body_background']; ?>" id="colorpickerHolder" class="text-input small-input" /></p><label>Выбрать файл ( размер <span class="rezolution">1280 x 800</span> )</label><input type="file" name="uploaded_banner_1280" /><br /><br />                        <label>Выбрать файл ( размер <span class="rezolution">1366 x 768</span> )</label><input type="file" name="uploaded_banner_1366" /><br /><br />                                                   <label>Выбрать файл ( размер <span class="rezolution">1600 x 900</span> )</label><input type="file" name="uploaded_banner_1600" /><br /><br />                                                   <label>Выбрать файл ( размер <span class="rezolution">1920 x 1080</span> )</label><input type="file" name="uploaded_banner_1920" /><br /><br />');



        var current_sel = $('select[name=position] option:selected').val();
        if(current_sel == 1)
        {
            $('.rezolution').text('730x380');
            file_upload_container.html('');
            file_upload_container.html(file_input);
            $('.rezolution').text('730x380');

        }

        if(current_sel == 5)
        {
            $('.rezolution').text('730x150');
            file_upload_container.html('');
            file_upload_container.html(file_input);
            $('.rezolution').text('730x150');
        }

        if(current_sel == 3)
        {
            $('.rezolution').text('730x70');
            file_upload_container.html('');
            file_upload_container.html(file_input);
            $('.rezolution').text('730x70');
        }

        if(current_sel == 4)
        {
            $('.rezolution').text('225x300');
            file_upload_container.html('');
            file_upload_container.html(file_input);
            $('.rezolution').text('225x300');
        }

        if(current_sel == 2)
        {
            file_upload_container.html('');
            file_upload_container.html(many_input);

            $('#colorpickerHolder').ColorPicker({
                onSubmit: function(hsb, hex, rgb, el) {
                    $(el).val(hex);
                    $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                }
            })
                .bind('keyup', function(){
                    $(this).ColorPickerSetColor(this.value);
                });
        }






        position.change(function(){

            var current = $(this).val();

            if(current == 1)
            {
                file_upload_container.html('');
                file_upload_container.html(file_input);
                $('.rezolution').text('730x380');
            }

            if(current == 5)
            {
                file_upload_container.html('');
                file_upload_container.html(file_input);
                $('.rezolution').text('730x150');
            }

            if(current == 3)
            {
                file_upload_container.html('');
                file_upload_container.html(file_input);
                $('.rezolution').text('730x70');
            }

            if(current == 4)
            {
                file_upload_container.html('');
                file_upload_container.html(file_input);
                $('.rezolution').text('225x300');
            }



            if(current == 2)
            {

                file_upload_container.html('');
                file_upload_container.html(many_input);

                $('#colorpickerHolder').ColorPicker({
                    onSubmit: function(hsb, hex, rgb, el) {
                        $(el).val(hex);
                        $(el).ColorPickerHide();
                    },
                    onBeforeShow: function () {
                        $(this).ColorPickerSetColor(this.value);
                    }
                })
                    .bind('keyup', function(){
                        $(this).ColorPickerSetColor(this.value);
                    });

            }



        });// change function end



    }); // ready end
</script>








<div class="clear"></div>



</div>
</div>
