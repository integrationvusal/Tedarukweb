<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    die("Неверные параметры скрипта");
}

$sql = $pdo->query("SELECT * FROM `details` WHERE `id` = ".$pdo->quote(intval($_GET['id']))." LIMIT 1");
$current = $sql->fetch(PDO::FETCH_ASSOC);

$regions = array();
$sql = $pdo->query("SELECT * FROM `regions` ORDER BY `id` ASC");
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
    $regions[] = $row;
}

$parent = array();
$sql = $pdo->query('SELECT * FROM `categories` ORDER BY `parent_id` DESC');
while ($rql = $sql->fetch(PDO::FETCH_ASSOC)) {
    $parent[] = $rql;
}

$levels = array();
$tree = array();
$cur = array();

foreach ($parent as $rows) {
    $cur = &$levels[$rows['id']];
    $cur['parent_id'] = $rows['parent_id'];
    $cur['category'] = $rows['category'];
    $cur['id'] = $rows['id'];

    if ($rows['parent_id'] == 0) {
        $tree[$rows['id']] = &$cur;
    } else {
        $levels[$rows['parent_id']]['children'][$rows['id']] = &$cur;
    }
}

function getTree($arr,$cur)
{
    global $current;
    $out = '';
    $out .= '<ul style="padding-left: 20px; margin-bottom: 5px;">';
        foreach ($arr as $k => $v) {
            $out .= '<li><input '.($v['id'] == $cur ? "checked=\"checked\"" : "").' class="checkParent" name="checkParent" type="checkbox" value="'.$v['id'].'">&nbsp;'.($v['parent_id'] == 0 ? '<b>'.$v['category'].'</b>' : $v['category']);
                if (!empty($v['children'])) {
                    $out .= getTree($v['children'],$cur);
                }
            $out .= '</li>';
        }
    $out .= '</ul>';
    return $out;
}

$cat_id = intval(@$_POST['checkParent']);
$structure_name = @$_POST['structure_name'];
$structure_address = @$_POST['structure_address'];
$surname = @$_POST['surname'];
$name = @$_POST['name'];
$mname = @$_POST['mname'];
$position = @$_POST['position'];
$email = @$_POST['email'];
$phone = @$_POST['phone'];
$m_phone = @$_POST['m_phone'];
$internal_phone = @$_POST['internal_phone'];

$structure_region = @$_POST['structure_region'];
$structure_district = @$_POST['structure_district'];

$edit_data = @$_POST['edit_data'];
$datetime = date('Y-m-d H:i:s');

if (isset($edit_data)) {
    $pdo->query("UPDATE `details` SET
		`cat_id` = " . $pdo->quote($cat_id) . ",
		`structure_name` = " . $pdo->quote($structure_name) . ",
		`structure_address` = " . $pdo->quote($structure_address) . ",
        `structure_region` = " . $pdo->quote(intval($structure_region)) . ",
		`structure_district` = " . $pdo->quote(intval($structure_district)) . ",
		`surname` = " . $pdo->quote($surname) . ",
		`name` = " . $pdo->quote($name) . ",
		`mname` = " . $pdo->quote($mname) . ",
		`position` = " . $pdo->quote($position) . ",
		`email` = " . $pdo->quote($email) . ",
		`phone` = " . $pdo->quote($phone) . ",
		`m_phone` = " . $pdo->quote($m_phone) . ",
		`internal_phone` = " . $pdo->quote($internal_phone) . "
		WHERE `id` = " . $pdo->quote(intval($_GET['id'])) . "
		LIMIT 1"
    );
    $return_url = 'index.php?page=c_data';?>
    <script>
        alert('Изменено успешно.');
        window.location = "<?php echo $return_url; ?>";
    </script>
<?php } ?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Изменить данные</h3>
        <ul class="content-box-tabs">
            <li><a href="#tab1" class="default-tab">Общие</a></li>
        </ul>
        <div class="clear"></div>
    </div>
    <div class="content-box-content">

        <form method="POST" enctype="multipart/form-data">
            <div>
                <div class="tab-content default-tab" id="tab1">
                    <div class="section">
                        <ul class="tabs">
                            <?php $sflang1 = $pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
                            while ($rflang1 = $sflang1->fetch(PDO::FETCH_ASSOC)) {
                                $tab_visible_li = ($rflang1['language_dir'] == DEFAULT_LANG_DIR) ? 'class="current"' : '';?>
                                <li <?php echo $tab_visible_li; ?>><?php echo $rflang1['language_name']; ?></li>
                            <?php } ?>
                        </ul>
                        <?php $sflang2 = $pdo->query("SELECT * FROM `cs_language_list` ORDER BY `language_id` ASC");
                        while ($rflang2 = $sflang2->fetch(PDO::FETCH_ASSOC)) {
                            $tab_visible = ($rflang2['language_dir'] == DEFAULT_LANG_DIR) ? ' visible' : '';?>
                            <div class="box <?php echo $tab_visible; ?>">
                                <div class="langs_cont_div">
                                    <p>
                                        <label>Организация (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="structure_name" value="<?php echo $current['structure_name']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Адрес (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="structure_address" value="<?php echo $current['structure_address']; ?>"/>
                                    </p>

                                    <p>
                                        <label>Регион (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <select class="small-input" name="structure_region" id="structure_region">
                                            <option value="0" disabled selected>Выберите</option>
                                            <?php foreach($regions as $r) {
                                                echo '<option '.($r['id'] == $current['structure_region'] ? 'selected="selected"' : '').' value="'.$r['id'].'">'.$r['region'].'</option>';
                                            }?>
                                        </select>
                                    </p>
                                    <p id="districts" style="display: none;">
                                        <label>Район (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <select class="small-input" name="structure_district" id="structure_district">
                                            <option value="0" disabled selected>Выберите</option>
                                        </select>
                                    </p>

                                    <p>
                                        <label>Фамилия (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="surname" value="<?php echo $current['surname']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Имя (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="name" value="<?php echo $current['name']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Отчество (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="mname" value="<?php echo $current['mname']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Позиция (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="position" value="<?php echo $current['position']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Email (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="email" value="<?php echo $current['email']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Телефон (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="phone" value="<?php echo $current['phone']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Мобильный (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="m_phone" value="<?php echo $current['m_phone']; ?>"/>
                                    </p>
                                    <p>
                                        <label>Внутренний (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="internal_phone" value="<?php echo $current['internal_phone']; ?>"/>
                                    </p>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php } ?>
                        <p style="padding-left: 12px;">
                            <label id="parent">[+] Структура</label>
                            <div id="checkboxes">
                                <!--<ul>
                                    <li>-->
                                        <!--<input <?php /*echo $current['cat_id'] == 0 ? 'checked="checked"' : '';*/?> type="checkbox" name="checkParent" class="checkParent" value="0"/>&nbsp;Родитель-->
                                        <?php echo getTree($tree,$current['cat_id']); ?>
                                    <!--</li>
                                </ul>-->
                            </div>
                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div>
                <p><input class="button" type="submit" name="edit_data" value="Сохранить"/></p>
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.checkParent').click(function () {
            if (!$(this).is(':checked')) {
                $('.checkParent').prop('disabled', false);
            } else {
                $('.checkParent').prop('disabled', true);
                $(this).prop('disabled', false);
            }
        })

        $('div#checkboxes').hide();

        $('#parent').click(function() {
            $('div#checkboxes').toggle('slow');
        });

        region_id = $('select#structure_region').val();
        $.ajax({
            url: 'actions/pages/ajax.php',
            type: 'post',
            dataType: 'json',
            data: 'action=getDistricts&id='+region_id,
            success: function(data) {
                $('select#structure_district option').remove();
                $('p#districts').show();
                $(data).each(function(i, v) {
                    $('select#structure_district').append('<option '+(v.id == '<?php echo $current['structure_district']?>' ? 'selected="selected"' : '')+' value="'+v.id+'">'+ v.district+'</option>');
                })
            }
        })

        $(document).on('change','#structure_region',function() {
            id = $(this).val();

            $.ajax({
                url: 'actions/pages/ajax.php',
                type: 'post',
                dataType: 'json',
                data: 'action=getDistricts&id='+id,
                success: function(data) {
                    $('select#structure_district option').remove();
                    $('p#districts').show();
                    $(data).each(function(i, v) {
                        $('select#structure_district').append('<option value="'+v.id+'">'+ v.district+'</option>');
                    })
                }
            })
        })
    })
</script>

<style>
    label#parent {
        background-color: #d3d3d3;
        padding: 10px;
    }
</style>