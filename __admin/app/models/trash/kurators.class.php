<?php

if (!defined("_VALID_PHP")) {die();}

class kurators {
	public static $db;
	private static $curators_tbl = 'curators';
	private static $curators_users_ref_tbl = 'curators_users';
	private static $users_tbl = 'users';
	private static $user_roles_ref_tbl = 'roles_users';
	private static $roles = 'roles';

	public static function getCurators() {
		$sql = "SELECT * FROM `".self::$curators_tbl."` ORDER BY title";
		$st = self::$db->query($sql);
		if ($st) {
			$curators = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
			return $curators;
		} else {
			var_export(self::$db->errorinfo());
			return false;
		}
	}

	public static function getCuratorUsers($curator_id, $getAsSetOfIDs=false) {
		if (empty($curator_id)) {return false;} else {$curator_id = intval($curator_id);}
		$sql = "SELECT u.id AS plain_user_id, u.username AS plain_user_name
			FROM `".self::$curators_users_ref_tbl."` r
				LEFT JOIN `".self::$users_tbl."` u ON u.id=r.user_id
			WHERE r.curator_id='{$curator_id}'";
		$res = self::$db->query($sql);
		if ($getAsSetOfIDs) {
			$roles = array();
			if ($res) {
				while ($role=$res->fetch(PDO::FETCH_ASSOC)) {
					$roles[] = $role['plain_user_id'];
				}
				$res->closeCursor();
			}
		} else {
			$roles = array();
			if ($res) {
				$roles = $res->fetchAll(PDO::FETCH_ASSOC);
				$res->closeCursor();
			}
		}
		return $roles;
	}

	public static function getUsersList() {
		$sql = "SELECT u.id, u.username, u.email
			FROM `".self::$users_tbl."` u
				LEFT JOIN `".self::$user_roles_ref_tbl."` ur ON ur.user_id=u.id
				LEFT JOIN `".self::$roles."` r ON r.id=ur.role_id
			WHERE r.name='invitor' OR r.name='receptionist'
			GROUP BY u.id
			ORDER BY u.username ASC";
		$st = self::$db->query($sql);
		$users = array();
		if ($st) {
			$users = $st->fetchAll(PDO::FETCH_ASSOC);
			$st->closeCursor();
		} else {
			die(var_export(self::$db->errorinfo(), true));
		}

		return $users;
	}

	public static function delCurator($kurator_id) {
		if (empty($kurator_id)) {return false;} else {$kurator_id = intval($kurator_id);}
		if ($kurator_id>0) {
			self::$db->query("DELETE FROM `".self::$curators_tbl."` WHERE id='{$kurator_id}'");
			return true;
		}
		return false;
	}

	public static function addCurator() {
		global $utils, $admin_lang, $kurators_lang;

		$kurator = array();
		$errors = array();
		if (!empty($_POST['title'])) {
			$kurator['title'] = self::$db->quote($_POST['title']);
		}
		if (empty($_POST['email']) || !$utils->checkEmailStrict($_POST['email'])) {
			$errors[] = $kurators_lang['email_err'];
		} else {
			$kurator['email'] = self::$db->quote($_POST['email']);
		}

		if (count($errors)) {
			$utils->showNotice('error', $errors);
		} else {
			$st = self::$db->query("INSERT INTO `".self::$curators_tbl."` (`".implode("`, `", array_keys($kurator))."`) VALUES (".implode(", ", array_values($kurator)).")");
			//if (!$st) {var_export(self::$db->errorinfo()); return $kurator;}
			$inserted = $st->rowCount();
			$st->closeCursor();

			if ($inserted) {
				$kurator_id = self::$db->lastInsertId();
				if (!empty($kurator_id)) {
					if (isset($_POST['users'])) if (is_array($_POST['users']) && count($_POST['users'])) {
						foreach ($_POST['users'] as $user_id) {
							$st = self::$db->query("INSERT INTO `".self::$curators_users_ref_tbl."` (`curator_id`, `user_id`) VALUES ('{$kurator_id}', '{$user_id}')");
							$st->closeCursor();
						}
					}
				}

				$utils->showNotice('success', $admin_lang['insert_suc']);
				$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_kurators&'.time(), 3000);
			} else {
				$utils->showNotice('error', $admin_lang['insert_err']);
			}
		}

		$kurator = $_POST;
		return $kurator;
	}

	public static function editCurator($kurator_id) {
		global $utils, $admin_lang, $kurators_lang;

		if (empty($kurator_id)) {return false;} else {$kurator_id = intval($kurator_id);}

		$kurator_res = self::$db->query("SELECT id, title, email FROM `".self::$curators_tbl."` WHERE id='{$kurator_id}' LIMIT 1");
		//if (!$kurator_res) {var_export(self::$db->errorinfo());}
		$kurator = $kurator_res->fetch(PDO::FETCH_ASSOC);

		if (empty($kurator['id'])) {return false;}

		if (isset($_POST['edit_data'])) {
			// validate input data and fill updatable data array
			$upd = $kurator;
			$errors = array();
			$upd['title'] = self::$db->quote(@$_POST['title']);
			// print "<pre>\n".var_export($upd, true)."\n</pre>";

			$post_users = array();
			if (!empty($_POST['users'])) {
				if (is_array($_POST['users']) && count($_POST['users'])) {
					$post_users = $_POST['users'];
				}
			}

			if (count($errors)) {
				$utils->showNotice('error', $errors);
			} else {
				$updated = false;
				unset($upd['id'], $upd['email']);
				if (count($upd)) {
					$upd_str = '';
					foreach ($upd as $fname=>$fval) {
						$upd_str.=(empty($upd_str)? '': ', ')."`{$fname}`=".$fval."";
					}
					$upd_sql = "UPDATE `".self::$curators_tbl."` SET {$upd_str} WHERE id='{$kurator_id}' LIMIT 1";
					$affected = self::$db->exec($upd_sql);
					$updated = ($affected!==false);
				}

				// reset user to roles references according to administrator's input
				$refs_changed = false;
				$old_users = self::getCuratorUsers($kurator_id, true);
				//print "<pre>".var_export()."\n\n""</pre>";
				$users_deletable = array_diff($old_users, $post_users);
				$users_insertable = array_diff($post_users, $old_users);
				if (is_array($users_deletable) && count($users_deletable)) { // delete obsolete references between users and roles
					$refs_changed = true;
					$res = self::$db->query("DELETE FROM `".self::$curators_users_ref_tbl."` WHERE curator_id='{$kurator_id}' AND user_id IN ('".implode("', '", $users_deletable)."')");
					$deleted = $res->rowCount();
					$res->closeCursor();
				}
				if (is_array($users_insertable) && count($users_insertable)) { // add new references between users and roles
					$refs_changed = true;
					foreach ($users_insertable as $new_user_id) {
						self::$db->exec("INSERT INTO `".self::$curators_users_ref_tbl."` (`curator_id`, `user_id`) VALUES (".self::$db->quote($kurator_id).", ".self::$db->quote($new_user_id).")");
					}
				}

				if ($updated || $refs_changed) {
					$utils->showNotice('success', $admin_lang['update_suc']);
					$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_kurators&'.time(), 3000);
				} else {
					$utils->showNotice('warning', $admin_lang['update_no_data_affected']);
					$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_kurators&'.time(), 3000);
				}
			}
		}

		return $kurator;
	}
}

?>