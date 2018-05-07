<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if( isset($_POST['save']) )
{

    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $query1 = $pdo->prepare("INSERT INTO `cs_colors` (`name_ru`, `name_en`, `name_az`, `color`, `alias`) VALUES (:name_ru, :name_en, :name_az, :color, :color_alias)");

    $query1->bindParam(':name_ru' , $name_ru);
    $query1->bindParam(':name_en' , $name_en);
    $query1->bindParam(':name_az' , $name_az);
    $query1->bindParam(':color' , $color);
    $query1->bindParam(':color_alias' , $color_alias);

    $name_ru = $_POST['name_ru'];
    $name_en = $_POST['name_en'];
    $name_az = $_POST['name_az'];
    $color_alias = $_POST['color_alias'];
    $color = '#'.$_POST['color_color'];

    $query1->execute();

    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=color'</script>";
}

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Добавить цвет</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <form name="add_tag" action="" method="POST">
            <p><label>Название цвета (RU)</label><input type="text" name="name_ru" value="" class="text-input small-input" /></p>
            <p><label>Название цвета (EN)</label><input type="text" name="name_en" value="" class="text-input small-input" /></p>
            <p><label>Название цвета (AZ)</label><input type="text" name="name_az" value="" class="text-input small-input" /></p>

            <p><label>Alias цвета ( max  10 символов )</label><input type="text" name="color_alias" value="" class="text-input small-input" /></p>

            <p><label>Цвет</label><input type="text"  id="colorpickerHolder" name="color_color" value="" class="text-input small-input" /></p>

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