<?php

if (!defined("_VALID_PHP"))
    die('Direct access to this location is not allowed.');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
    die("Неверные параметры скрипта");
}

$sql = $pdo->query("SELECT * FROM `categories` WHERE `id` = " . $pdo->quote(intval($_GET['id'])) . " LIMIT 1");
$current = $sql->fetch(PDO::FETCH_ASSOC);

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

$parent_id = intval(@$_POST['checkParent']);
$category = @$_POST['category'];
$edit_section = @$_POST['edit_section'];
$datetime = date('Y-m-d H:i:s');

if (isset($edit_section)) {
    $pdo->query("UPDATE `categories` SET
		`parent_id` = " . $pdo->quote($parent_id) . ",
		`category` = " . $pdo->quote($category) . "
		WHERE `id` = " . $pdo->quote(intval($_GET['id'])) . "
		LIMIT 1"
    );
    $return_url = 'index.php?page=c_sections';?>
    <script>
        alert('Изменено успешно.');
        window.location = "<?php echo $return_url; ?>";
    </script>
<?php } ?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Изменить раздел</h3>
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
                                        <label>Название (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="category"
                                               value="<?php echo $current['category']; ?>"/>
                                    </p>
                                </div>
                                <div class="clear"></div>
                            </div>
                        <?php } ?>
                        <p style="padding-left: 12px;">
                            <label>Родитель</label>
                            <div id="checkboxes">
                                <ul>
                                    <li>
                                        <input <?php echo $current['parent_id'] == 0 ? 'checked="checked"' : '';?> type="checkbox" name="checkParent" class="checkParent" value="0"/>&nbsp;Родитель
                                        <?php echo getTree($tree,$current['parent_id']); ?>
                                    </li>
                                </ul>
                            </div>
                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div>
                <p><input class="button" type="submit" name="edit_section" value="Сохранить"/></p>
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
    })
</script>