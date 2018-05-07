<?php


if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

$sfcle = $pdo->query("SELECT * FROM `cs_content_list` WHERE `content_delete`='no' AND `content_id`=" . (int)$_GET['id'] . " LIMIT 1");
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
$catalogue_pos = @$_POST['catalogue_pos'];
$url = @$_POST['url'];
$hide = @$_POST['hide'];
$search = @$_POST['search'];
$on_page = @$_POST['on_page'];
$template = @$_POST['template'];
$on_menu = @$_POST['on_menu'];
$under_menu = @$_POST['under_menu'];
$menu_position = @$_POST['menu_position'];
$side_position = @$_POST['side_position'];
$menu_link = @$_POST['menu_link'];
$photo = @$_POST['photo'];
$features = @$_POST['feature_id'];
$save_new_resource = @$_POST['save_new_resource'];
$upload_photo = @$_POST['upload_photo'];
$datetime = date('Y-m-d H:i:s');
$new = (@$_POST['new_menu'])? 1 : 0;


$on_menu = (strlen($on_menu) > 0) ? 'yes' : 'no';
$hide = (strlen($hide) > 0) ? 'yes' : 'no';
$search = (strlen($search) > 0) ? 'no' : 'yes';

$show_on_menu = ($rfcle['content_show_on_menu'] == 'yes') ? 'CHECKED="CHECKED"' : '';
$hide_on_site = ($rfcle['content_hide_page'] == 'yes') ? 'CHECKED="CHECKED"' : '';
$hide_on_search = ($rfcle['content_show_search'] == 'no') ? 'CHECKED="CHECKED"' : '';

if (isset($save_new_resource)) {


    if (is_file($_FILES["photo"]["tmp_name"])) {

        $file = @$_FILES["photo"]["tmp_name"];
        $fot = 'resource_' . date('Ymdhis') . '.png';
        $upload_photo = "`content_photo`=" . $pdo->quote($fot) . ",";

        @mkdir(ROOT . "/uploads/resources/big/", 0777, true);
        @mkdir(ROOT . "/uploads/resources/small/", 0777, true);
        @mkdir(ROOT . "/uploads/resources/mini/", 0777, true);

        @resize($file, ROOT . "/uploads/resources/big/" . $fot, 460,false);
        @resize($file, ROOT . "/uploads/resources/small/" . $fot, 154,false);
        @resize($file, ROOT . "/uploads/resources/mini/" . $fot, 80,false);

    }
    if (!is_file($_FILES["photo"]["tmp_name"])) {
        $upload_photo = '';
    }


    $pdo->query("UPDATE `cs_content_list` SET
			`content_page_type`=" . $pdo->quote($page_type) . ",
			`catalogue_pos`=" . $pdo->quote($catalogue_pos) . ",
			`url`=" . $pdo->quote($url) . ",
			`content_show_on_menu`=" . $pdo->quote($on_menu) . ",
			`content_menu_position`=" . (int)$menu_position . ",
			`side_position`=" . (int)$side_position . ",
			`content_under_menu`=" . (int)$under_menu . ",
			`content_menu_link`=" . $pdo->quote($menu_link) . ",
			`content_template_id`=" . (int)$template . ",
			`content_hide_page`=" . $pdo->quote($hide) . ",
			`new` = ".$pdo->quote($new).",
			`content_show_search`=" . $pdo->quote($search) . ",
			" . $upload_photo . "
			`content_pagetitle_az`=" . $pdo->quote(htmlspecialchars($content_pagetitle_az)) . ",
			`content_description_az`=" . $pdo->quote(htmlspecialchars($content_description_az)) . ",
			`content_text_az`=" . $pdo->quote($content_text_az) . ",
			`content_pagetitle_ru`=" . $pdo->quote(htmlspecialchars($content_pagetitle_ru)) . ",
			`content_description_ru`=" . $pdo->quote(htmlspecialchars($content_description_ru)) . ",
			`content_text_ru`=" . $pdo->quote($content_text_ru) . ",
			`content_pagetitle_en`=" . $pdo->quote(htmlspecialchars($content_pagetitle_en)) . ",
			`content_description_en`=" . $pdo->quote(htmlspecialchars($content_description_en)) . ",
			`content_text_en`=" . $pdo->quote($content_text_en) . ",
			`content_ins_date`=" . $pdo->quote($datetime) . " WHERE `content_delete`='no' AND `content_id`=" . (int)$_GET['id'] . " LIMIT 1");

    if (!empty($features)) {
        $pdo->query('DELETE FROM `cs_categoy_features` WHERE `content_id` = '.(int)$_GET['id'].'')->execute();
        foreach ($features as $item) {
            $sql = "INSERT INTO `cs_categoy_features` (`content_id`, `feature_id`) VALUES (:content_id, :feature_id)";
            $query = $pdo->prepare($sql);
            $query->execute(array(
                ':content_id' => (int)$_GET['id'],
                ':feature_id' => $item
            ));
        }
    }

    if (isset($_GET['rtn']) AND @$_GET['rtn'] == 'news') {
        $return_url = 'index.php?page=news';
    } else {
        $return_url = ($on_menu == 'yes') ? 'index.php?page=menu' : 'index.php?page=page';
    }

    ?>
    <script>
        alert('Сохранено успешно.');
        window.location = "<?php echo $return_url; ?>&st=<?php echo htmlspecialchars($_GET['rsp']); ?>";
    </script>
<?php

}

?>
<div class="content-box">
<div class="content-box-header">
    <h3>Новый ресурс</h3>
    <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab">Общие</a></li>
        <li><a href="#tab2">Настройка страницы</a></li>
        <li><a href="#tab3">Настройка меню</a></li>
    </ul>
    <div class="clear"></div>
</div>
<div class="content-box-content">

<form method="POST" enctype="multipart/form-data">
<div>
<div class="tab-content default-tab" id="tab1">

    <div class="section">
        <ul class="tabs">
            <?php

            $sflang1 = $pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
            while ($rflang1 = $sflang1->fetch(PDO::FETCH_ASSOC)) {

                $tab_visible_li = ($rflang1['language_dir'] == DEFAULT_LANG_DIR) ? 'class="current"' : '';

                ?>
                <li <?php echo $tab_visible_li; ?>><?php echo $rflang1['language_name']; ?></li>
            <?php
            }
            ?>
        </ul>
        <?php

        $sflang2 = $pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
        while ($rflang2 = $sflang2->fetch(PDO::FETCH_ASSOC)) {

            $tab_visible = ($rflang2['language_dir'] == DEFAULT_LANG_DIR) ? ' visible' : '';

            ?>
            <div class="box <?php echo $tab_visible; ?>">
                <div class="langs_cont_div">

                    <p>
                        <label>Название (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                        <input value="<?php echo $rfcle['content_pagetitle_' . $rflang2['language_dir']]; ?>"
                               class="text-input medium-input" type="text"
                               name="content_pagetitle_<?php echo $rflang2['language_dir']; ?>"/>
                    </p>

                    <p>
                        <label>Заголовок (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                        <textarea name="content_description_<?php echo $rflang2['language_dir']; ?>" cols="69"
                                  rows="7"><?php echo $rfcle['content_description_' . $rflang2['language_dir']]; ?></textarea>
                    </p>

                    <p>
                        <label>Контент (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                        <textarea name="content_text_<?php echo $rflang2['language_dir']; ?>" cols="69"
                                  rows="15"><?php echo $rfcle['content_text_' . $rflang2['language_dir']]; ?></textarea>
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

<div class="tab-content" id="tab2">
    <div>

        <?php

        if (strlen($rfcle['content_photo']) > 0) {
            ?>
            <div style="border: 1px solid #ccc; padding: 1px; float: right; width: 180px; height: 180px;">
                <img src="../uploads/resources/small/<?php echo $rfcle['content_photo']; ?>" width="180" height="180"/>
            </div>
        <?php
        }

        ?>

        <p>
            <label>Публикация</label>
            <input <?php echo $hide_on_site; ?> type="checkbox" name="hide"/> Не отображать на сайте
        </p>

        <p>
            <label>Доступен для поиска</label>
            <input <?php echo $hide_on_search; ?> type="checkbox" name="search"/> Не показывать при поиске
        </p>

        <p>
            <label>Добавить в</label>
            <select name="on_page" class="small-input">

                <option value="0">Выберите родителя</option>
                <?php
                $sfop = $pdo->query("
                        SELECT `content_id`, `content_page_type`, `content_delete`, `content_hide_page`, `content_pagetitle_" . DEFAULT_LANG_DIR . "`
                        FROM
                            `cs_content_list`
                        ORDER BY
                            `content_id` ASC
                    ")->fetchAll(PDO::FETCH_ASSOC);


                foreach ($sfop as $item) {
                    $select_on_page = ($rfcle['content_on_page'] == $item['content_id']) ? 'selected="selected"' : '';
                    ?>
                    <option <?php echo $select_on_page ?> value="<?php echo $item['content_id']; ?>"><?php echo $item['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></option>
                <?php } ?>

            </select>
        </p>

        <p>
            <label>Url</label>
            <input class="text-input medium-input" type="text" name="url" value="<?php echo $rfcle['url'] ?>" />
        </p>

        <script>
            $(document).ready(function () {
                $("select.page_type").change(function () {
                    var page_type = $("select.page_type option:selected").attr("value");
                    if (page_type == 5) {
                        $("#redirect").show(1000);
                    } else {
                        $("#redirect").hide(1000);
                    }
                })


            })


        </script>
        <p>
            <label>Тип страницы</label>
            <select name="page_type" class="small-input page_type">
                <?php
                    $sfptl = $pdo->query("SELECT * FROM `cs_page_type` ORDER BY `page_type_id` ASC")->fetchAll(PDO::FETCH_ASSOC);;
                    foreach ($sfptl as $item) { ?>
                        <?php if ($rfcle['content_page_type'] == $item['type']): ?>
                            <option selected="selected" value="<?php echo $item['type']; ?>"><?php echo $item['type']; ?></option>
                        <?php else: ?>
                            <option value="<?php echo $item['type']; ?>"><?php echo $item['type']; ?></option>
                        <?php endif?>
                <?php } ?>
            </select>
        </p>

        <p>
            <label>Раздел в каталоге</label>
            <select name="catalogue_pos" class="small-input page_type">
                <?php
                $sfptl = array(0, 1, 2, 3);
                foreach ($sfptl as $item) { ?>
                    <?php if ($rfcle['catalogue_pos'] == $item): ?>
                        <option selected="selected" value="<?php echo $item; ?>"><?php echo $item; ?></option>
                    <?php else: ?>
                        <option value="<?php echo $item; ?>"><?php echo $item; ?></option>
                    <?php endif?>
                <?php } ?>
            </select>
        </p>

        <?php
            $cur_features = $pdo->query('SELECT `feature_id` FROM `cs_categoy_features` WHERE `content_id` = '.$pdo->quote($_GET['id']).'')->fetchAll(PDO::FETCH_COLUMN);
            $options = $pdo->query('
                SELECT `cs_product`.*, `s_options`.`feature_id`, `s_features`.`id` as feature_id, `s_features`.`name`
                FROM `cs_product`
                LEFT JOIN `cs_products_cat` ON `cs_products_cat`.`product_id` = `cs_product`.`id`
                LEFT JOIN `s_options` ON `s_options`.`product_id` = `cs_product`.`simpla_id`
                LEFT JOIN `s_features` ON `s_features`.`id` = `s_options`.`feature_id`
                WHERE `cs_products_cat`.`cat_id` = '.(int)$_GET['id'].'
                AND `s_options`.`value` IS NOT NULL
                GROUP BY `s_features`.`id`
            ')->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <p>
            <label>Фильтры</label>
            <?php foreach ($options as $item):?>
                <?php if (in_array($item['feature_id'], $cur_features)): ?>
                    <div><label class="list"><input checked="checked" class="filter_list" name="feature_id[]" type="checkbox" value="<?php echo $item['feature_id']; ?>"/><strong><?php echo $item['name']; ?></strong></label></div>
                <?php else: ?>
                    <div><label class="list"><input class="filter_list" name="feature_id[]" type="checkbox" value="<?php echo $item['feature_id']; ?>"/><strong><?php echo $item['name']; ?></strong></label></div>
                <?php endif ?>
            <?php endforeach ?>
        </p>




        <p <? if (empty($rfcle['content_menu_link'])) echo 'style="display: none;"'; ?> id="redirect">
            <label>Внешняя ссылка <em>(перенаправление)</em></label>
            <input class="text-input" value="<?php echo $rfcle['content_menu_link']; ?>" type="text" name="menu_link"/>
        </p>

    <!--    <p>
            <label>Шаблон</label>
            <select name="template" class="small-input">
                <?php /*

                $sftmpl = $pdo->query("SELECT * FROM `cs_site_templates` ORDER BY `template_id` ASC");
                while ($rftmpl = $sftmpl->fetch(PDO::FETCH_ASSOC)) {

                    $select_template = ($rfcle['content_template_id'] == $rftmpl['template_id']) ? 'SELECTED="SELECTED"' : '';

                    ?>
                    <option <?php echo $select_template; ?>
                        value="<?php echo $rftmpl['template_id']; ?>"><?php echo $rftmpl['template_name']; ?></option>
                <?php
                } */

                ?>
            </select>
        </p> -->

        <p>
            <label>Позиция меню<label>
                    <select name="side_position" class="small-input">
                        <?php
                            $i = 1;
                            $sides = array('Main', 'Left');
                            foreach ($sides as $item):
                            $select_template = ($rfcle['side_position'] == $i) ? 'SELECTED="SELECTED"' : '';
                        ?>
                        <option <?php echo $select_template; ?> value="<?php echo $i ?>"><?php echo $item ?></option>
                        <?php $i++; endforeach ?>
                    </select>
        </p>

        <p>
            <label>Картинкa</label>
            <input type="file" name="photo"/>
        </p>

    </div>
    <div class="clear"></div>
</div>

<div class="tab-content" id="tab3">
    <div>

        <p>
            <label>Показывать в меню</label>
            <input <?php echo $show_on_menu; ?> type="checkbox" name="on_menu"/> Показать на сайте как меню
        </p>

        <p>
            <label>Подменю</label>
            <select name="under_menu" class="small-input">
                <option value="0"></option>
                <?php

                $sfum = $pdo->query("SELECT `content_id`, `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "` FROM `cs_content_list` WHERE `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=0 ORDER BY `content_id` DESC");
                while ($rfum = $sfum->fetch(PDO::FETCH_ASSOC)) {

                    $select_under_menu = ($rfcle['content_under_menu'] == $rfum['content_id']) ? 'SELECTED="SELECTED"' : '';

                    ?>
                    <option <?php echo $select_under_menu; ?>
                        value="<?php echo $rfum['content_id']; ?>"><?php echo $rfum['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></option>
                    <?php


                    $sfum2 = $pdo->query("SELECT `content_id`, `content_delete`, `content_hide_page`, `content_show_on_menu`, `content_under_menu`, `content_pagetitle_" . DEFAULT_LANG_DIR . "` FROM `cs_content_list` WHERE `content_delete`='no' AND `content_hide_page`='no' AND `content_show_on_menu`='yes' AND `content_under_menu`=" . (int)$rfum['content_id'] . " ORDER BY `content_id` DESC");


                    if ($sfum2) {

                        while ($rfum2 = $sfum2->fetch(PDO::FETCH_ASSOC)) {

                            $select_under_menu2 = ($rfcle['content_under_menu'] == $rfum2['content_id']) ? 'SELECTED="SELECTED"' : '';

                            ?>
                            <option <?php echo $select_under_menu2; ?> value="<?php echo $rfum2['content_id']; ?>">
                                --- <?php echo $rfum2['content_pagetitle_' . DEFAULT_LANG_DIR]; ?></option>
                        <?php
                        }

                    }
                }

                ?>
            </select>
        </p>


        <p>
            <label>Позиция в меню</label>
            <input class="text-input" value="<?php echo $rfcle['content_menu_position']; ?>" type="text"
                   name="menu_position"/>
        </p>

        <p>
            <label><input type="checkbox" <?php echo ($rfcle['new'])?  "checked='checked'" : '' ; ?>  value="1"  name="new_menu"/> Новый раздел</label>
        </p>


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