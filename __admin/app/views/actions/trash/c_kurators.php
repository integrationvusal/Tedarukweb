<?php

if (!defined("_VALID_PHP")) {die();}

include 'actions/langs/kurators_ru.lang.php';
require_once 'actions/plugins/kurators.class.php';
kurators::$db = $pdo;


if (!empty($_GET['delete'])) {
	$deleted = kurators::delCurator($_GET['delete']);
	if ($deleted) {
		print '<script type="text/javascript">
			alert(\''.$admin_lang['del_suc'].'\');
			window.location.href = \'?page='.$_GET['page'].'&amp;'.$time.'\';
		</script>';
	} else {
		print '<script type="text/javascript">
			alert(\''.$admin_lang['del_err'].'\');
			window.location.href = \'?page='.$_GET['page'].'&amp;'.$time.'\';
		</script>';
	}
}

$list = kurators::getCurators();
//print "<pre>\n".var_export($list, true)."\n</pre>";

?>

<div class="content-box">
    <div class="content-box-header">
        <h3>Кураторы</h3>

        <div class="clear"></div>
    </div>

	<div class="content-box-content">
		<form name="menu_list" action="" method="post">
			<table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
                <thead>
					<tr>
						<th id="d_id">ID</th>
						<th id="d_name">ФИО</th>
						<th id="d_email">E-mail</th>
						<th id="d_users">Назначен пользователям</th>
						<th><?php print $admin_lang['controls']; ?></th>
					</tr>
                </thead>
                <tbody>
					<?php
						if (is_array(@$list) && count(@$list)) {
							foreach ($list as $kurator) {
					?>
					<tr>
						<td><?php print $kurator['id']; ?></td>
						<td><?php print $kurator['title']; ?></td>
						<td><?php print $kurator['email']; ?></td>
						<td><?php
							$users = kurators::getCuratorUsers($kurator['id']);
							//print "<pre>\n".var_export($users, true)."\n</pre>";
							if (!empty($users)) foreach ($users as $i=>$user) {
								print ($i? ', ': '').$user['plain_user_name'];
							}
						?></td>
						<td>
                            <a href="?page=c_edit_kurator&amp;id=<?php print $kurator['id']; ?>&amp;<?php print $time; ?>" title="<?php print $admin_lang['edit']; ?>"><img src="<?php print SITE.TPL_DIR; ?>images/edit.png" class="list-ico" alt="" /></a>

                            <a href="?page=c_kurators&amp;delete=<?php print $kurator['id']; ?>&amp;<?php print $time; ?>" title="<?php print $admin_lang['delete']; ?>" onclick="return confirm('<?php print $admin_lang['delete_confirmation']; ?>');"><img src="<?php print SITE.TPL_DIR; ?>images/icons/cross.png" alt="" /></a>

                            <span class="loader" style="visibility: hidden;"><img src='templates/default/images/2.gif' alt="" /></span>
						</td>
					</tr>
					<?php
							}
						} else {
							print '<td colspan="6">'.$admin_lang['no_data_found'].'</td>';
						}
					?>
				</tbody>
			</table>
		</form>
	</div>