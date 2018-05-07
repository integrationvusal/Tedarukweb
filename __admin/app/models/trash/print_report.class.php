<?php

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class print_report {
	public static $db;
	private static $invites_tbl = 'tnc_invites';
	private static $users_tbl = 'users';
	private static $user_roles_ref_tbl = 'roles_users';
	private static $roles = 'roles';

	public static function getRecords() {
		global $utils, $default_date_since;

		// get author, guest, purpose, status, invitation time, visit start time, visit end time, card No, add date
		$records = array();

		$filter = array();
		if (!empty($_GET['invitors'])) if (is_array($_GET['invitors']) && count($_GET['invitors'])) {
			$inv = array();
			foreach ($_GET['invitors'] as $inv_id) {
				$inv[] = intval($inv_id);
			}
			$filter[] = "inv.id IN ('".implode("', '", $inv)."')";
		}
		if (!empty($_GET['status'])) {
			if ($_GET['status']=='finished') {
				$filter[] = "rec.visit_end IS NOT NULL";
			} else if ($_GET['status']=='cancelled') {
				$filter[] = "rec.is_cancelled='1'";
			} else if ($_GET['status']=='started') {
				$filter[] = "rec.visit_start IS NOT NULL AND rec.visit_end IS NULL";
			} else if ($_GET['status']=='planned') {
				$filter[] = "rec.is_cancelled='0' AND rec.visit_start IS NULL AND rec.invitation_time>='".$utils->date2stamp(date('d.m.Y'))."'";
			} else if ($_GET['status']=='expired') {
				$filter[] = "rec.is_cancelled='0' AND rec.visit_start IS NULL AND rec.invitation_time<'".$utils->date2stamp(date('d.m.Y'))."'";
			}
		}
		if (!empty($_GET['since']) && $utils->valid_date(@$_GET['since'])) {
			$filter[] = "rec.invitation_time>='".$utils->date2stamp($_GET['since'])."'";
		}
		if (!empty($default_date_since) && $utils->valid_date(@$default_date_since)) {
			$filter[] = "rec.invitation_time>='".$utils->date2stamp($default_date_since)."'";
		}
		if (!empty($_GET['till']) && $utils->valid_date(@$_GET['till'])) {
			$filter[] = "rec.invitation_time<='".$utils->date2stamp($_GET['till'])."'";
		}
		$where = (count($filter)? ('WHERE '.implode(' AND ', $filter)): '');

		$sql = "SELECT inv.username AS invitor, inv.email AS invitor_mail, inv.displayname AS invitor_title, rec.name AS guest_name,
				rec.surname AS guest_surname, rec.company_name, rec.purpose, rec.invitation_time,
				rec.card_id, rec.visit_start, rec.visit_end, rec.is_cancelled, rec.add_date
			FROM `".self::$invites_tbl."` rec
				LEFT JOIN `".self::$users_tbl."` inv ON inv.id=rec.invited_by
			{$where}
			ORDER BY inv.username ASC, rec.add_date DESC";
		// print "<pre>\n{$sql}\n</pre>";
		$st = self::$db->query($sql);
		if ($st) {
			$records = $st->fetchAll(PDO::FETCH_ASSOC);
		} else {
			var_export(self::$db->errorinfo());
		}

		return $records;
	}

	public static function getUsersList() {
		$sql = "SELECT u.id, u.username, u.email, u.displayname
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





	public static function getUsers() {
		//$sql = $pdo->query("SELECT * FROM `details` ORDER BY `" . $name_sort_sel . "` " . $name_ord_sel . " LIMIT " . ($count * $j) . " ," . ($count) . "");
		$res = self::$db->query("SELECT id, email, username, logins, last_login FROM `".self::$users_tbl."` ORDER BY username");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}

	public static function getUserRoles($uid, $getAsSetOfIDs=false) {
		if (empty($uid)) {return false;} else {$uid = intval($uid);}
		$sql = "SELECT r.id AS role_id, r.name AS role_name
			FROM `".self::$user_role_ref_tbl."` r_ref
				LEFT JOIN `".self::$roles_tbl."` r ON r.id=r_ref.role_id
			WHERE r_ref.user_id='{$uid}'";
		//print "<pre>{$sql}</pre>";
		$res = self::$db->query($sql);
		//print var_export($res);
		if ($getAsSetOfIDs) {
			$roles = array();
			if ($res) {
				while ($role=$res->fetch(PDO::FETCH_ASSOC)) {
					$roles[] = $role['role_id'];
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

	public static function getRolesList() {
		$sql = "SELECT * FROM `".self::$roles_tbl."`";
		$res = self::$db->query($sql);
		//print "<pre>SELECT * FROM `".self::$roles_tbl."`\n\n".var_export($res, true)."</pre>";
		//$roles = $res->fetchAll(PDO::FETCH_ASSOC);
		if ($res) {
			$roles = $res->fetchAll(PDO::FETCH_ASSOC);
			$res->closeCursor();
		} /*else {
			die(var_export(self::$db->errorinfo(), TRUE));
			$roles = array();
		}*/

		return $roles;
	}

	public static function delUser($uid) {
		if (empty($uid)) {return false;} else {$uid = intval($uid);}
		if ($uid>0) {
			self::$db->query("DELETE FROM `".self::$users_tbl."` WHERE id='{$uid}'");
			return true;
		}
		return false;
	}

	public static function addUser() {
		global $utils, $admin_lang, $kohana_users_lang;

		$user = array();
		$errors = array();
		if (empty($_POST['username']) || !$utils->checkLogin($_POST['username'])) {
			$errors[] = $kohana_users_lang['username_err'];
		} else {
			$user['username'] = $_POST['username'];
		}
		if (empty($_POST['email']) || !$utils->checkEmailStrict($_POST['email'])) {
			$errors[] = $kohana_users_lang['email_err'];
		} else {
			$user['email'] = $_POST['email'];
		}
		if (empty($_POST['password']) || !$utils->checkPass($_POST['password'])) {
			$errors[] = $kohana_users_lang['password_invalid_err'];
		} else {
			$user['password'] = self::getPwdHash($_POST['password']);
		}

		if (count($errors)) {
			$utils->showNotice('error', $errors);
		} else {
			$st = self::$db->query("INSERT INTO `".self::$users_tbl."` (`".implode("`, `", array_keys($user))."`) VALUES ('".implode("', '", array_values($user))."')");
			$inserted = $st->rowCount();
			$st->closeCursor();

			if ($inserted) {
				$user_id = self::$db->lastInsertId();
				if (!empty($user_id)) {
					if (isset($_POST['roles'])) if (is_array($_POST['roles']) && count($_POST['roles'])) {
						foreach ($_POST['roles'] as $role_id) {
							$st = self::$db->query("INSERT INTO `".self::$user_role_ref_tbl."` (`user_id`, `role_id`) VALUES ('{$user_id}', '{$role_id}')");
							$st->closeCursor();
						}
					}
				}

				$utils->showNotice('success', $admin_lang['insert_suc']);
				$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_kohana_users&'.time(), 3000);
			} else {
				$utils->showNotice('error', $admin_lang['insert_err']);
			}
		}

		return $user;
	}

	private static function getPwdHash($pwd) {
		/*
			Generates password hash
			with php function hash_hmac('encryption_algorythm', 'given_password', 'encryption_salt')
			the same way as in modules/auth/classes/Kohana/Auth.php
			encryption algorithm and salt defined in modules/auth/config/auth.php
			overrides by application/config/auth.php

			NOTE: it's not recommended to include kohana's config files in CMS. CMS must preserve independency from output as much as it possible.

			TODO: in order to avoid duplication of settings find a way to use common settings in CMS and Kohana (or yii or any other framework). For example, store them in database or in XML files in CMS subdirectory.
		*/
		return hash_hmac('md5', $pwd, '3#~tL*');
	}

	public static function editUser($uid) {
		global $utils, $admin_lang, $kohana_users_lang;

		if (empty($uid)) {return false;} else {$uid = intval($uid);}

		$user_res = self::$db->query("SELECT id, email, username FROM `".self::$users_tbl."` WHERE id='{$uid}' LIMIT 1");
		$user = $user_res->fetch(PDO::FETCH_ASSOC);

		if (empty($user['id'])) {return false;}

		if (isset($_POST['edit_data'])) {
			// validate input data and fill updatable data array
			$upd = $user;
			$errors = array();
			if (!empty($_POST['password'])) {
				if ($utils->checkPass($_POST['password'])) {
					//$password_hash = md5($_POST['password']);
					$password_hash = getPwdHash($_POST['password']);
					$upd['password'] = $password_hash;
				} else {
					$errors[] = $kohana_users_lang['password_invalid_err'];
				}
			}
			$post_roles = array();
			if (!empty($_POST['roles'])) {
				if (is_array($_POST['roles']) && count($_POST['roles'])) {
					$post_roles = $_POST['roles'];
				}
			}

			if (count($errors)) {
				$utils->showNotice('error', $errors);
			} else {
				$updated = false;
				unset($upd['id'], $upd['email'], $upd['username']);
				if (count($upd)) {
					$upd_str = '';
					foreach ($upd as $fname=>$fval) {
						$upd_str.=(empty($upd_str)? '': ', ')."`{$fname}`=".self::$db->quote($fval)."";
					}
					$upd_sql = "UPDATE `".self::$users_tbl."` SET {$upd_str} WHERE id='{$uid}' LIMIT 1";
					$affected = self::$db->exec($upd_sql);
					$updated = ($affected!==false);
				}

				// reset user to roles references according to administrator's input
				$refs_changed = false;
				$old_roles = self::getUserRoles($uid, true);
				$roles_deletable = array_diff($old_roles, $post_roles);
				$roles_insertable = array_diff($post_roles, $old_roles);
				if (is_array($roles_deletable) && count($roles_deletable)) { // delete obsolete references between users and roles
					$refs_changed = true;
					//$this->admin->sqlDelete($db_pre.'category_article', "aid='{$_GET['edit']}' AND cid IN (" . implode(',', $del) . ")");
					//$deleted = self::$db->exec("DELETE FROM `".self::$user_role_ref_tbl."` WHERE user_id='{$uid}' AND role_id IN ('".implode("', '", $roles_deletable)."')");
					$res = self::$db->query("DELETE FROM `".self::$user_role_ref_tbl."` WHERE user_id='{$uid}' AND role_id IN ('".implode("', '", $roles_deletable)."')");
					$deleted = $res->rowCount();
					$res->closeCursor();
					//print "<pre>".var_export($deleted, true)."\n\n".var_export($deleted->fetchAll(PDO::FETCH_ASSOC), true)."</pre>";
					//print "<pre>".var_export($deleted, true)."</pre>";
					/*if ($deleted) {
						self::$db->exec("OPTIMIZE TABLE `".self::$user_role_ref_tbl."`");
					}*/
				}
				if (is_array($roles_insertable) && count($roles_insertable)) { // add new references between users and roles
					$refs_changed = true;
					/* foreach ($ins as $category_id) {
						$this->db->add($tblCatRel, array(
							'brand_id' => $id,
							'category_id' => $category_id
						), false);
					} */
					foreach ($roles_insertable as $new_role_id) {
						self::$db->exec("INSERT INTO `".self::$user_role_ref_tbl."` (`user_id`, `role_id`) VALUES (".self::$db->quote($uid).", ".self::$db->quote($new_role_id).")");
					}
				}

				if ($updated || $refs_changed) {
					$utils->showNotice('success', $admin_lang['update_suc']);
					// $utils->redirect(SITE.CMS_DIR.'?page=c_kohana_users&'.time());
					$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_kohana_users&'.time(), 3000);
				} else {
					// $utils->showNotice('error', $admin_lang['update_err']);
					$utils->showNotice('warning', $admin_lang['update_no_data_affected']);
				}
			}
		}

		$user['roles'] = self::getUserRoles($uid, true);

		return $user;
	}
}

?>