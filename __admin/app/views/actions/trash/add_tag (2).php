<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if( isset($_POST['save']) )
{
    $query1 = $pdo->prepare("INSERT INTO `cs_tags` (`name`,`color`) VALUES (:name , :color)");

    $query1->bindParam(':name' , $name);
    $query1->bindParam(':color' , $color);

    $name = $_POST['tag_name'];
    $color = '#'.$_POST['tag_color'];

    $query1->execute();

    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=list_tags'</script>";
}

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Добавить метку</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">

       <form name="add_tag" action="" method="POST">
        <p><label>Название метки</label><input type="text" name="tag_name" value="" class="text-input small-input" /></p>
        <p><label>Цвет метки</label><input type="text"  id="colorpickerHolder" name="tag_color" value="" class="text-input small-input" /></p>
           <p><input type="submit" name="save" value="Сохранить" class="button" /></p>
       </form>


<script type="text/javascript" language="javascript">
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
</script>
        <div class="clear"></div>
    </div>
</div>