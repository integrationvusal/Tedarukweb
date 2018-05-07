<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

$sfcle = $pdo->query("SELECT * FROM `cs_letter_temp` WHERE  `id`=" . (int)$_GET['id'] . " LIMIT 1");
$rfcle = $sfcle->fetch(PDO::FETCH_ASSOC);

$sfl = $pdo->query('SELECT * FROM cs_language_list ORDER BY `language_id` ASC');
while ($rfl = $sfl->fetch(PDO::FETCH_ASSOC)) {
    $pt = 'content_pagetitle_' . $rfl['language_dir'];
    $$pt = @$_POST['content_pagetitle_' . $rfl['language_dir']];

    $pd = 'content_description_' . $rfl['language_dir'];
    $$pd = @$_POST['content_description_' . $rfl['language_dir']];

    $pc = 'content_text_' . $rfl['language_dir'];
    $$pc = @$_POST['content_text_' . $rfl['language_dir']];

}

$page_type = @$_POST['page_type'];
$url = @$_POST['url'];
$hide = @$_POST['hide'];
$search = @$_POST['search'];
$on_page = @$_POST['on_page'];
$template = @$_POST['template'];
$under_menu = @$_POST['under_menu'];
$menu_position = @$_POST['menu_position'];
$menu_link = @$_POST['menu_link'];
$photo = @$_POST['photo'];
$save_new_resource = @$_POST['save_new_resource'];
$upload_photo = @$_POST['upload_photo'];
$datetime = date('Y-m-d H:i:s');

//$on_menu = (strlen($on_menu) > 0) ? 'yes' : 'no';
$hide = (strlen($hide) > 0) ? 'yes' : 'no';
$search = (strlen($search) > 0) ? 'no' : 'yes';

//$show_on_menu = ($rfcle['content_show_on_menu'] == 'yes') ? 'CHECKED="CHECKED"' : '';
//$hide_on_site = ($rfcle['hide_page'] == 'yes') ? 'CHECKED="CHECKED"' : '';
//$hide_on_search = ($rfcle['show_search'] == 'no') ? 'CHECKED="CHECKED"' : '';

if (isset($save_new_resource)) {

 $pdo->query("UPDATE `cs_letter_temp` SET			
					
			`name_az`=" . $pdo->quote(htmlspecialchars($content_pagetitle_az)) . ",
			
			`text_az`=" . $pdo->quote($content_text_az) . ",
			
			`name_ru`=" . $pdo->quote(htmlspecialchars($content_pagetitle_ru)) . ",
			
			`text_ru`=" . $pdo->quote($content_text_ru) . ",
			
			`name_en`=" . $pdo->quote(htmlspecialchars($content_pagetitle_en)) . ",
			
			`text_en`=" . $pdo->quote($content_text_en) . "
			
			 WHERE `id`=".$pdo->quote($_GET['id'])."");


/*
$pdo->query("UPDATE `cs_letter_temp` SET `name_az`='Changed' WHERE `id`='4' ");
*/

echo "<script language='javascript' type='text/javascript'>
	 alert('Сохранение успешно');
	 window.location = 'index.php?page=list_letter_temp';
	
	</script>";
}



?>
<div class="content-box">
<div class="content-box-header">
    <h3>Шаблоны писем</h3>
   
    <div class="clear"></div>
</div>
<div class="content-box-content">

<form method="POST" enctype="multipart/form-data">
<div>
<div class="tab-content default-tab" id="tab1">

    <div class="section">
        <ul class="tabs">
           <!-- <?php

            $sflang1 = $pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
            while ($rflang1 = $sflang1->fetch(PDO::FETCH_ASSOC)) {

                $tab_visible_li = ($rflang1['language_dir'] == DEFAULT_LANG_DIR) ? 'class="current"' : '';

                ?>
                <li <?php echo $tab_visible_li; ?>><?php echo $rflang1['language_name']; ?></li>
            <?php
            }
            ?>-->

        </ul>
        <?php

        $sflang2 = $pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
        while ($rflang2 = $sflang2->fetch(PDO::FETCH_ASSOC)) {

            $tab_visible = ($rflang2['language_dir'] == DEFAULT_LANG_DIR) ? ' visible' : '';

            ?>
            <div class="box <?php echo $tab_visible; ?>">
                <div class="langs_cont_div">

                    <p>
                        <label>Название </label>
                        <input value="<?php echo $rfcle['name_' . $rflang2['language_dir']]; ?>"
                               class="text-input medium-input" type="text"
                               name="content_pagetitle_<?php echo $rflang2['language_dir']; ?>"/>
                    </p>
					

                    <p>
                        <label>Контент</label>
                        <textarea name="content_text_<?php echo $rflang2['language_dir']; ?>" cols="69"
                                  rows="15"><?php echo $rfcle['text_' . $rflang2['language_dir']]; ?></textarea>
                        <script>
                            CKEDITOR.replace("content_text_<?php echo $rflang2['language_dir']; ?>",
                                {
                                    uiColor: '#f9f9f9',
                                    filebrowserBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html",
                                    filebrowserImageBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Images",
                                    filebrowserFlashBrowseUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/ckfinder.html?type=Flash",
                                    filebrowserUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files",
                                    filebrowserImageUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images",
                                    filebrowserFlashUploadUrl: "<?php echo SITE; ?>/amazonmanager/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash"
                                });
                        </script>
                    </p>

                </div>
                <div class="clear"></div>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="clear"></div>
</div>


</div>
<div>

    <p>
        <input class="button" type="submit" name="save_new_resource" value="Сохранить"/>
    </p>

</div>
</form>

<div class="clear"></div>
</div>
</div>