<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');


if( isset($_GET['id']) && !empty($_GET['id']) )
{
  $id = (int)$_GET['id'];
}
else
{
    die("Неверные параметры скрипта");
}


if(  isset($_GET['action']) && $_GET['action'] == 'delete'  )
{
  $del_res = $pdo->exec("DELETE FROM `cs_feature_value` WHERE `id`=".$pdo->quote($id)."");
  echo "<script language='javascript' type='text/javascript'>window.location = 'index.php?page=list_features'</script>";
}
$sfl = $pdo->query('SELECT * FROM cs_language_list ORDER BY `language_id` ASC');
while ($rfl = $sfl->fetch(PDO::FETCH_ASSOC)) {
    $pt = 'content_pagetitle_' . $rfl['language_dir'];
    $$pt = @$_POST['content_pagetitle_' . $rfl['language_dir']];
}
$save_new_resource = @$_POST['save_new_resource'];
if (isset($save_new_resource)) {
    $feature_id = (int)$_POST['feature_id'];

    $pdo->query("
        UPDATE `cs_feature_value` SET
            `value_az` = " . $pdo->quote($content_pagetitle_az) . ",
            `value_ru` = " . $pdo->quote($content_pagetitle_ru) . ",
            `value_en` = " . $pdo->quote($content_pagetitle_en) . ",
            `feature_id` = " . $pdo->quote($feature_id) . "
         WHERE `id`=" . $pdo->quote($id) . "
     ");

    if (isset($_GET['rtn']) AND @$_GET['rtn'] == 'list_features') {
        $return_url = 'index.php?page=list_features';
    } else {
        //$return_url = ($on_menu == 'yes') ? 'index.php?page=menu' : 'index.php?page=page';
    }

    ?>
    <script>
        alert('Сохранено успешно.');
        window.location = "<?php echo $return_url; ?>&st=<?php echo htmlspecialchars($_GET['rsp']); ?>";
    </script>
<?php

}

$sfcle = $pdo->query("SELECT * FROM `cs_feature_value` WHERE  `id`=" . $pdo->quote($id) . " LIMIT 1");
$rfcle = $sfcle->fetch(PDO::FETCH_ASSOC);


?>
<div class="content-box">
<div class="content-box-header">
    <h3>Редактировать свойство</h3>
    <ul class="content-box-tabs">
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
                        <input value="<?php echo $rfcle['value_' . $rflang2['language_dir']]; ?>"
                               class="text-input medium-input" type="text"
                               name="content_pagetitle_<?php echo $rflang2['language_dir']; ?>"/>
                    </p>

                </div>
                <div class="clear"></div>
            </div>
        <?php
        }
        ?>
        <?php
        $features = $pdo->query("
                SELECT `id`, `name`
                FROM
                    `s_features`
                ORDER BY
                    `name` ASC
            ")->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <p>
            <label>Родитель</label>
            <select name="feature_id" class="small-input">
                <option value="0">Выберите свойство</option>
                <?php foreach ($features as $item): ?>
                    <?php if ($item['id'] == $rfcle['feature_id']): ?>
                        <option selected="selected" value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php else: ?>
                        <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
                    <?php endif ?>
                <?php endforeach ?>
            </select>
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