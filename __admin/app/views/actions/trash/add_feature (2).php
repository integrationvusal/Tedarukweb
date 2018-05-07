<?php
    if (!defined("_VALID_PHP"))
        die('Direct access to this location is not allowed.');

    $sfl = $pdo->query('SELECT * FROM cs_language_list ORDER BY `language_id` ASC');
    while ($rfl = $sfl->fetch(PDO::FETCH_ASSOC)) {
        $pt = 'content_pagetitle_' . $rfl['language_dir'];
        $$pt = @$_POST['content_pagetitle_' . $rfl['language_dir']];
    }
    if (isset($_POST['save_new_resource'])) {
        $feature_id = (int)$_POST['feature_id'];
        $pdo->query("INSERT INTO `cs_feature_value` SET
                `value_az` = " . $pdo->quote($content_pagetitle_az) . ",
                `value_ru` = " . $pdo->quote($content_pagetitle_ru) . ",
                `value_en` = " . $pdo->quote($content_pagetitle_en) . ",
                `feature_id` = " . $pdo->quote($feature_id) . "
        ");
     }
?>

<div class="content-box">
<div class="content-box-header">
    <h3>Новое свойство</h3>

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
                            <input class="text-input medium-input" type="text"
                                   name="content_pagetitle_<?php echo $rflang2['language_dir']; ?>"/>
                        </p>

                    </div>

                <div class="clear"></div>
            </div>
        <?php } ?>

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
                    <option value="<?php echo $item['id']; ?>"><?php echo $item['name']; ?></option>
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