<?php

if (!defined("_VALID_PHP")) {die();}

$link = $utils->trueLink(array('page'));
$link_x = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');

$page = 1;
$total = 1;
if (isset($_GET['st'])) if ($_GET['st']>1) {
	$page = intval($_GET['st']);
}

include 'actions/langs/kohana_users_ru.lang.php';
require_once 'actions/plugins/kohana_users.class.php';
kohana_users::$db = $pdo;

$order = @$_GET['order'];
$sort = @$_GET['sort'];
$st = @$_GET['st'];

if (is_array($order)) {
    $order = array_shift($order);
}
if (is_array($sort)) {
    $sort = array_shift($sort);
}
if (is_array($st)) {
    $st = array_shift($st);
}

$order = htmlspecialchars($order);
$sort = htmlspecialchars($sort);
$st = htmlspecialchars($st);


if (!empty($_GET['delete'])) {
	$deleted = kohana_users::delUser($_GET['delete']);
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

/*if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $sql = $pdo->query("DELETE FROM `details` WHERE `id`=".$pdo->quote(intval($_GET['delete'])));
    echo "<script type=\"text/javascript\">window.location.href = '?page={$_GET['page']}';</script>";
}*/

$list = kohana_users::getUsers();
//print "<pre>\n".var_export($list, true)."\n</pre>";

?>

<div class="content-box">
    <div class="content-box-header">
        <h3><?php print $kohana_users_lang['users_list']; ?></h3>

        <!-- searchbox start -->
        <div class="search_box">
            <form action="" method="get" id="search_form">
				<input type="hidden" name="page" value="<?php print @$_GET['page']; ?>" />
				<input type="hidden" name="<?php print $time; ?>" value="" />
				<?php
					if (isset($_GET['roles'])) {
						print $utils->array2form(array('roles' => $_GET['roles']));
					}
				?>

                <input type="text" name="search_query" value="<?php print @$_GET['search_query'];?>" placeholder="Поиск пользователей" />
                <input type="submit" name="go_search" value="Поиск" />
            </form>
        </div>
        <!-- searchbox end -->

        <div class="clear"></div>
    </div>

	<div class="content-box-filter">
		<form action="" method="get" id="fltForm">
			<input type="hidden" name="page" value="<?php print @$_GET['page']; ?>" />
			<input type="hidden" name="<?php print time(); ?>" value="" />
			<input type="hidden" name="search_query" value="<?php print @$_GET['search_query']; ?>" />

			<div class="pickUsers" style="margin: 10px; display: inline-block; vertical-align: top;">
				<p>Фильтр по ролям (правам):</p>

				<div style="border: 1px solid #d5d5d5; border-radius: 5px; width: 440px; height: 100px; padding: 10px 0 10px 10px;">
					<div style="background: #fff; height: 100px; overflow: auto;">
						<?php
							$roles = kohana_users::getRolesList();
							if (is_array(@$roles) && count(@$roles)) {
								foreach ($roles as $role) {
									print '<input type="checkbox" name="roles[]" value="'.$role['id'].'"'.((@in_array($role['id'], @$_GET['roles']) || empty($_GET['roles']))? ' checked="checked"': '').' onclick="this.form.submit();" id="role_'.$role['id'].'" /> <label for="role_'.$role['id'].'" style="display: inline; font-weight: normal;">'.$role['name'].' - '.$role['description'].'</label><br />';
								}
							}
						?>
					</div>
				</div>
			</div>
		</form>
	</div>

	<div class="content-box-content">
		<form name="menu_list" action="" method="post">
			<table width="100%" cellpadding="0" cellspacing="0" id="myTable" class="tablesorter">
                <thead>
					<tr>
						<th id="d_id">ID</th>
						<th id="d_displayname"><?php print $kohana_users_lang['displayname']; ?></th>
						<th id="d_username"><?php print $kohana_users_lang['username']; ?></th>
						<th id="d_roles"><?php print $kohana_users_lang['roles']; ?></th>
						<th id="d_loged_times"><?php print $kohana_users_lang['loged_times']; ?></th>
						<th id="d_last_login"><?php print $kohana_users_lang['last_login']; ?></th>
						<th><?php print $admin_lang['controls']; ?></th>
					</tr>
                </thead>
                <tbody>
					<?php
						if (is_array(@$list) && count(@$list)) {
							foreach ($list as $usr) {
					?>
					<tr>
						<td><?php print $usr['id']; ?></td>
						<td><?php print $usr['displayname']; ?></td>
						<td><?php print $usr['username']; ?></td>
						<td><?php
							$roles = kohana_users::getUserRoles($usr['id']);
							if (is_array(@$roles) && count(@$roles)) {
								foreach ($roles as $i=>$role) {
									print ($i? ', ': '').$role['role_name'];
								}
							}
						?></td>
						<td><?php print $usr['logins']; ?></td>
						<td><?php print (empty($usr['last_login'])? '': date('Y.m.d H:i', $usr['last_login'])); ?></td>
						<td>
                            <a href="?page=c_edit_kohana_user&amp;id=<?php print $usr['id']; ?>&amp;rsp=<?php print htmlspecialchars($st); ?>&amp;<?php print $time; ?>" title="<?php print $admin_lang['edit']; ?>"><img src="<?php print SITE.TPL_DIR; ?>images/edit.png" class="list-ico" alt="" /></a>

                            <a href="?page=c_kohana_users&amp;st=<?php print htmlspecialchars($st); ?>&amp;order=<?php print htmlspecialchars($order); ?>&amp;sort=<?php print htmlspecialchars($sort); ?>&amp;delete=<?php print $usr['id']; ?>&amp;<?php print $time; ?>" title="<?php print $admin_lang['delete']; ?>" onclick="return confirm('<?php print $admin_lang['delete_confirmation']; ?>');"><img src="<?php print SITE.TPL_DIR; ?>images/icons/cross.png" alt="" /></a>

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

			<div class="pagination">
				<?php print $utils->pg(array(
					'total' => $total,
					'current' => $page,
					'delimiter' => '',
					'page_url' => $link_x.'&amp;st=%d',
					'number_class' => 'page',
					'active_container_tagname' => 'span',
					'act_class' => 'current_page',
					'scope' => true,
					'scope_width' => 11,
					'scope_start_text' => '&laquo; в начало',
					'scope_start_class' => 'start_page',
					'scope_end_text' => 'в конец &raquo;',
					'scope_end_class' => 'end_page',
					'scope_prev_text' => '&lsaquo; назад',
					'scope_prev_class' => 'prev_page',
					'scope_next_text' => 'вперёд &rsaquo;',
					'scope_next_class' => 'next_page'
				)); ?>
			</div>
		</form>
	</div>