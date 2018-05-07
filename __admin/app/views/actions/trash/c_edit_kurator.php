<?php

if (!defined("_VALID_PHP")) {die();}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id'];
} else {
	print $admin_lang['get_err'];
    die();
}

include 'actions/langs/kurators_ru.lang.php';
require_once 'actions/plugins/kurators.class.php';
kurators::$db = $pdo;

$kurator = kurators::editCurator($id);

//print "<pre>\n".var_export($user, true)."\n</pre>"; die();

?>
<script src="<?php print SITE.TPL_DIR.'scripts/'; ?>utils.js" type="text/javascript"></script>

<div class="content-box">
	<div class="content-box-header">
		<h3>Редактировать куратора <?php print $kurator['title']; ?></h3>

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

						<div>
							<span style="font-weight: bold; padding: 0 0 10px;">E-mail:</span>
							<p style="font-family: monospace;"><?php print @$kurator['email']; ?></p>
						</div>

						<div>
							<span style="font-weight: bold; padding: 0 0 10px;">Пользователи:</span>

							<div style="border: 1px solid #d5d5d5; border-radius: 5px; width: 50%; height: 140px; padding: 10px 0 10px 10px; margin-top: 10px;">
							<div style="background: #fff; height: 140px; overflow: auto;">
								<?php
									$users = kurators::getUsersList();
									$users_appended = kurators::getCuratorUsers($kurator['id'], true);
									foreach ($users as $user) {
										$checked = in_array($user['id'], $users_appended);
										print '<div style="padding-bottom: 10px;">
											<input type="checkbox" name="users[]" value="'.$user['id'].'" id="inpRole_'.$user['id'].'"'.($checked? ' checked="checked"': '').' /> <label for="inpRole_'.$user['id'].'" style="display: inline; font-weight: normal;">'.$user['username'].' '.$user['email'].'</label>
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
                <p><input type="submit" name="edit_data" value="<?php print $admin_lang['save']; ?>" class="button" /></p>
                <!-- <p><input type="reset" name="reset" value="<?php print $admin_lang['reset']; ?>" class="button" /></p> -->
            </div>
        </form>
        <div class="clear"></div>
    </div>
</div>