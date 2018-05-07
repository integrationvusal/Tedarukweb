<?php

if (!defined("_VALID_PHP")) {die();}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
	print $admin_lang['get_err'];
    die();
}

include 'actions/langs/kohana_users_ru.lang.php';
require_once 'actions/plugins/kohana_users.class.php';
kohana_users::$db = $pdo;

$user = kohana_users::editUser($id);

//print "<pre>\n".var_export($user, true)."\n</pre>"; die();

?>

<!-- <script type="text/javascript">
	var genPwd = function(opts) {
		var length = 8;
		if (opts && opts.length) {length = opts.length;}
		var mode = 'default';
		if (opts && opts.mode) {
			if (opts.mode=='light') {mode = 'light';}
		}
		var use_sets = ['uppercase', 'string', 'numeric', 'punctuation'];
		if (opts && opts.use_sets && opts.use_sets.length) {
			use_sets = opts.use_sets;
		}

		var sets = {
			'uppercase': {
				'default': 'ABCDEFGHIJKLNOPQRSTUVWXYZ',
				'light': 'ABCDEFGHJKNPQRSTUVWXYZ'
			},
			'string': {
				'default': 'abcdefghijklnopqrstuvwxyz',
				'light': 'abcdefghijknpqrstuvwxyz'
			},
			'numeric': {
				'default': '0123456789',
				'light': '23456789'
			},
			'punctuation': {
				'default': '!@#$%^&*()_+~`|}{[]\:;?><,./-=',
				'light': '!@#$^&*_~;?-'
			}
		};
		var symbols_allowed = '';
		var set = '';
		for (set in use_sets) {
			if (sets[use_sets[set]]) {
				symbols_allowed+=sets[use_sets[set]][mode];
			}
		}

		var pwd = '';
		while (pwd.length<length) {
			pwd+=symbols_allowed.charAt(Math.floor(Math.random()*symbols_allowed.length));
		}

		return pwd;
	};
</script> -->
<script src="<?php print SITE.TPL_DIR.'scripts/'; ?>utils.js" type="text/javascript"></script>

<div class="content-box">
	<div class="content-box-header">
		<h3><?php print sprintf($kohana_users_lang['user_edit'], $user['username']); ?></h3>

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
							<label for="inpUsername"><?php print $kohana_users_lang['displayname']; ?> :</label>
							<input type="text" name="displayname" value="<?php print @$user['displayname']; ?>" class="text-input medium-input" />
						</div>

						<div>
							<span style="font-weight: bold; padding: 0 0 10px;"><?php print $kohana_users_lang['username']; ?>:</span>
							<p style="font-family: monospace;"><?php print $user['username']; ?></p>
						</div>

						<div>
							<span style="font-weight: bold; padding: 0 0 10px;">E-mail:</span>
							<p style="font-family: monospace;"><?php print $user['email']; ?></p>
						</div>

						<p> 
							<label for="inpPassword"><?php print $kohana_users_lang['password']; ?>:</label>
							<input type="text" name="password" value="" class="text-input medium-input" id="inpPassword" /> <a href="#" title="<?php print $kohana_users_lang['password_gen']; ?>" id="btnGenPwd" style="margin-left: 5px; padding: 6px; border: 1px solid #d5d5d5; border-radius: 5px;"><img src="<?php print SITE.TPL_DIR.'images/'; ?>refresh.gif" alt="<?php print $kohana_users_lang['password_gen']; ?>" style="vertical-align: middle;" /></a> <a href="#" title="clear" id="btnDelPwd" style="margin-left: 5px; padding: 6px; border: 1px solid #d5d5d5; border-radius: 5px;"><img src="<?php print SITE.TPL_DIR.'images/'; ?>delete.png" alt="clear" style="vertical-align: middle;" /></a><br />
							<span>* <?php print $kohana_users_lang['password_note']; ?></span>

							<script type="text/javascript">
	$('#btnGenPwd').click(function() {
		var pwd = genPwd({
			length: 8,
			mode: 'light',
			use_sets: ['string', 'numeric']
		});
		$('#inpPassword').val(pwd);

		return false;
	});
	$('#btnDelPwd').click(function() {
		$('#inpPassword').val('');

		return false;
	});
	/*document.getElementById('inpPassword').value = genPwd({
		length: 8,
		mode: 'light',
		use_sets: ['string', 'numeric']
	});*/
							</script>
						</p>

						<div>
							<span style="font-weight: bold; padding: 0 0 10px;"><?php print $kohana_users_lang['roles']; ?>:</span>

							<div style="border: 1px solid #d5d5d5; border-radius: 5px; width: 50%; height: 140px; padding: 10px 0 10px 10px; margin-top: 10px;">
							<div style="background: #fff; height: 140px; overflow: auto;">
								<?php
									$roles = kohana_users::getRolesList();
									// print "<pre>\n".var_export($roles, true)."\n</pre>";
									// print "<pre>\n".var_export($user, true)."\n</pre>";
									foreach ($roles as $role) {
										$checked = (is_array(@$user['roles']) && count(@$user['roles']) && in_array($role['id'], @$user['roles']));
										print '<div style="padding-bottom: 10px;">
											<input type="checkbox" name="roles[]" value="'.$role['id'].'" id="inpRole_'.$role['id'].'"'.($checked? ' checked="checked"': '').' /> <label for="inpRole_'.$role['id'].'" style="display: inline;">'.$role['name'].' <span style="font-weight: normal;">- '.$role['description'].'</span></label>
										</div>';
									}
								?>
							</div>
							</div>
						</div>

						<!-- <p>
							<label>Пароль:</label>
							<input type="text" name="structure_name" value="" class="text-input medium-input" />
						</p> -->
					</div>

                    <div class="clear"></div>
                </div>
            </div>
            <div style="padding-top: 20px;">
                <p><input type="submit" name="edit_data" value="<?php print $admin_lang['save']; ?>" class="button" /></p>
                <!-- <p><input type="reset" name="reset" value="<?php print $admin_lang['reset']; ?>" class="button" /></p> -->
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>
<?php

die();






/* old code */

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