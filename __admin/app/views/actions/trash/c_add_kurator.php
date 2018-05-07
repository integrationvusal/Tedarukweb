<?php

if (!defined("_VALID_PHP")) {die();}

include 'actions/langs/kurators_ru.lang.php';
require_once 'actions/plugins/kurators.class.php';
kurators::$db = $pdo;

if (isset($_POST['add'])) {
	$kurator = kurators::addCurator();
}

?>

<div class="content-box">
	<div class="content-box-header">
		<h3>Добавить куратора</h3>

		<ul class="content-box-tabs">
            <li><a href="#tab1" class="default-tab"><?php print $admin_lang['common_data']; ?></a></li>
        </ul>
        <div class="clear"></div>
    </div>

    <div class="content-box-content">
        <form action="" method="post">
            <div>
                <div class="tab-content default-tab" id="tab1">
					<div>
						<div style="margin-bottom: 20px;">
							<label for="inpUsername">ФИО:</label>
							<input type="text" name="title" value="<?php print @$kurator['title']; ?>" class="text-input medium-input" />
						</div>

						<div style="margin-bottom: 20px;">
							<label for="inpUsername">E-mail <span style="color: red;">*</span> :</label>
							<input type="text" name="email" value="<?php print @$kurator['email']; ?>" class="text-input medium-input" />
						</div>

						<div style="margin-top: 10px;">
							<span style="font-weight: bold; padding: 0 0 10px;">Пользователи:</span>

							<div style="border: 1px solid #d5d5d5; border-radius: 5px; width: 50%; height: 140px; padding: 10px 0 10px 10px; margin-top: 10px;">
							<div style="background: #fff; height: 140px; overflow: auto;">
								<?php
									$users = kurators::getUsersList();
									foreach ($users as $user) {
										//$checked = (is_array(@$user['roles']) && count(@$user['roles']) && in_array($role['id'], @$user['roles']));
										print '<div style="padding-bottom: 10px;">
											<input type="checkbox" name="users[]" value="'.$user['id'].'" id="inpRole_'.$user['id'].'"'.(@$checked? ' checked="checked"': '').' /> <label for="inpRole_'.$user['id'].'" style="display: inline; font-weight: normal;">'.$user['username'].' '.$user['email'].'</label>
										</div>';
									}
								?>
							</div>
							</div>
						</div>
					</div>

                    <div class="clear"></div>
                </div>
            </div>
            <div style="padding-top: 20px;">
                <p><input type="submit" name="add" value="<?php print $admin_lang['save']; ?>" class="button" /></p>
                <!-- <p><input type="reset" name="reset" value="<?php print $admin_lang['reset']; ?>" class="button" /></p> -->
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>









<?php
die();

	// OLD CODE



$parent = array();
$sql = $pdo->query('SELECT * FROM `categories` ORDER BY `parent_id` DESC');
while ($rql = $sql->fetch(PDO::FETCH_ASSOC)) {
    $parent[] = $rql;
}

$regions = array();
$sql = $pdo->query("SELECT * FROM `regions` ORDER BY `id` ASC");
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
    $regions[] = $row;
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

function getTree($arr)
{
    $out = '';
    $out .= '<ul style="padding-left: 20px; margin-bottom: 5px;">';
        foreach ($arr as $k => $v) {
            $out .= '<li><input class="checkParent" name="checkParent" type="checkbox" value="'.$v['id'].'">&nbsp;'.($v['parent_id'] == 0 ? '<b>'.$v['category'].'</b>' : $v['category']);
                if (!empty($v['children'])) {
                    $out .= getTree($v['children']);
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

$add_data = @$_POST['add_data'];
$datetime = date('Y-m-d H:i:s');

if (isset($add_data)) {
    $pdo->query("INSERT INTO `details` SET
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
		`internal_phone` = " . $pdo->quote($internal_phone) . ""
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
                                        <input class="text-input medium-input" type="text" name="structure_name" />
                                    </p>
                                    <p>
                                        <label>Адрес (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="structure_address" />
                                    </p>

                                    <p>
                                        <label>Регион (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <select class="small-input" name="structure_region" id="structure_region">
                                            <option value="0" disabled selected>Выберите</option>
                                            <?php foreach($regions as $r) {
                                                echo '<option value="'.$r['id'].'">'.$r['region'].'</option>';
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
                                        <input class="text-input medium-input" type="text" name="surname" />
                                    </p>
                                    <p>
                                        <label>Имя (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="name" />
                                    </p>
                                    <p>
                                        <label>Отчество (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="mname" />
                                    </p>
                                    <p>
                                        <label>Позиция (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="position" />
                                    </p>
                                    <p>
                                        <label>Email (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="email" />
                                    </p>
                                    <p>
                                        <label>Телефон (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="phone" />
                                    </p>
                                    <p>
                                        <label>Мобильный (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="m_phone" />
                                    </p>
                                    <p>
                                        <label>Внутренний (<?php echo strtoupper($rflang2['language_dir']); ?>)</label>
                                        <input class="text-input medium-input" type="text" name="internal_phone" />
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
                                        <!--<input type="checkbox" name="checkParent" class="checkParent" value="0"/>&nbsp;Родитель-->
                                        <?php echo getTree($tree); ?>
                                    <!--</li>
                                </ul>-->
                            </div>
                        </p>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div>
                <p><input class="button" type="submit" name="add_data" value="Сохранить"/></p>
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