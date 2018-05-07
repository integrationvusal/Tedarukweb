<?php

if(!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if( (isset($_GET['id'])) && ( !empty($_GET['id']) ) )
{
    $id = (int)$_GET['id'];
}

// Deleting tag

if( isset($_GET['action']) && ( $_GET['action']=='delete' ) )
{
    $res = $pdo->query("DELETE FROM `cs_colors` WHERE `id`=".$pdo->quote($id)."")->execute();
    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=color'</script>";
}



$query2 = $pdo->query("SELECT * FROM `cs_colors` WHERE `id`=".$pdo->quote($id)."")->fetch(PDO::FETCH_ASSOC);


if( isset($_POST['save']) )
{
    $query1 = $pdo->prepare("UPDATE `cs_colors` SET `name_ru`=:name_ru, `name_en`=:name_en, `name_az`=:name_az, `color` = :color, `alias` = :alias WHERE `id`=:id_c");

    $query1->bindParam(':name_ru' , $name_ru);
    $query1->bindParam(':name_en' , $name_en);
    $query1->bindParam(':name_az' , $name_az);
    $query1->bindParam(':color' , $color);
    $query1->bindParam(':alias' , $alias);
    $query1->bindParam(':id_c' , $id);

    $name_ru = $_POST['name_ru'];
    $name_en = $_POST['name_en'];
    $name_az = $_POST['name_az'];
    $alias = $_POST['color_alias'];
    $color = '#'.$_POST['color_color'];
    $id_c = $id;

    $query1->execute();
    echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=color'</script>";
}

?>
<div class="content-box">
    <div class="content-box-header">
        <h3>Редактировать цвет</h3>

        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <form name="add_tag" action="" method="POST">
            <p><label>Название цвета (RU)</label><input type="text" name="name_ru" value="<?=$query2['name_ru']?>" class="text-input small-input" /></p>
            <p><label>Название цвета (EN)</label><input type="text" name="name_en" value="<?=$query2['name_en']?>" class="text-input small-input" /></p>
            <p><label>Название цвета (AZ)</label><input type="text" name="name_az" value="<?=$query2['name_az']?>" class="text-input small-input" /></p>

            <p><label>Alias цвета ( max  10 символов )</label><input type="text" name="color_alias" value="<?=$query2['alias']?>" class="text-input small-input" /></p>

            <p><label>Цвет</label><input type="text"  id="colorpickerHolder" name="color_color" value="<?php echo ltrim ($query2['color'] , '#');  ?>" class="text-input small-input" /></p>

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