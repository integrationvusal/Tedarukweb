<?php

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class kohana_users {
	public static $db;
	private static $users_tbl = 'users';
	private static $user_role_ref_tbl = 'roles_users';
	private static $roles_tbl = 'roles';

	/*public static function getUsers() {
		//$sql = $pdo->query("SELECT * FROM `details` ORDER BY `" . $name_sort_sel . "` " . $name_ord_sel . " LIMIT " . ($count * $j) . " ," . ($count) . "");
		$res = self::$db->query("SELECT id, email, username, logins, last_login FROM `".self::$users_tbl."` ORDER BY username");
		$row = $res->fetchAll(PDO::FETCH_ASSOC);
		return $row;
	}*/

	public static function getUsers() {
		global $utils, $page, $total;

		$list = array();

		$filter = array();
		if (!empty($_GET['search_query'])) {
			$search_query = $utils->makeSearchable($_GET['search_query']);
			if ($search_query) {
				$filter[] = "u.displayname LIKE '%{$search_query}%'";
			}
		}
		if (!empty($_GET['roles'])) if (is_array($_GET['roles']) && count($_GET['roles'])) {
			$roles = array();
			foreach ($_GET['roles'] as $role) {
				$roles[] = intval($role);
			}
			$filter[] = "(SELECT r.role_id FROM `".self::$user_role_ref_tbl."` r WHERE r.role_id IN ('".implode("', '", $roles)."') AND r.user_id=u.id LIMIT 1) IS NOT NULL";
		}
		$where = (empty($filter)? '': (' WHERE '.implode(" AND ", $filter)));

		$npp = 10;

		$c_sql = "SELECT COUNT(u.id) AS c FROM `".self::$users_tbl."` u ".$where;
		$c_st = self::$db->query($c_sql);
		/* var_export(self::$db->errorinfo()); */
		$c_arr = $c_st->fetchAll(PDO::FETCH_ASSOC);
		$c = @$c_arr[0]['c'];
		if (empty($c)) {return false;}

		$total = ceil($c/$npp);
		if ($total>0) {
			$page = (($page>$total)? $total: $page);
			$start = ($page-1)*$npp;

			$list_sql = "SELECT u.id, email, u.username, u.displayname, u.logins, u.last_login
	FROM `".self::$users_tbl."` u
	{$where}
	ORDER BY u.username ASC
	LIMIT ".(($start>1)? ($start.', '): '').$npp;
			/* print "<pre>\n{$list_sql}\n</pre>"; */

			$list_st = self::$db->query($list_sql);
			$list = $list_st->fetchAll(PDO::FETCH_ASSOC);
			$list_st->closeCursor();
		}

		return $list;
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
		if (!empty($_POST['displayname'])) {
			$user['displayname'] = $_POST['displayname'];
		}
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
			$vals = array();
			foreach ($user as $val) {
				$vals[] = self::$db->quote($val);
			}
			$st = self::$db->query("INSERT INTO `".self::$users_tbl."` (`".implode("`, `", array_keys($user))."`) VALUES (".implode(", ", $vals).")");
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

		$user_res = self::$db->query("SELECT id, email, username, displayname FROM `".self::$users_tbl."` WHERE id='{$uid}' LIMIT 1");
		$user = $user_res->fetch(PDO::FETCH_ASSOC);

		if (empty($user['id'])) {return false;}

		if (isset($_POST['edit_data'])) {
			// validate input data and fill updatable data array
			$errors = array();
			if (!empty($_POST['password'])) {
				if ($utils->checkPass($_POST['password'])) {
					//$password_hash = md5($_POST['password']);
					$password_hash = getPwdHash($_POST['password']);
					$user['password'] = $password_hash;
				} else {
					$errors[] = $kohana_users_lang['password_invalid_err'];
				}
			}
			$user['displayname'] = $_POST['displayname'];
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
				$upd = $user;
				unset($upd['id'], $upd['email'], $upd['username']);
				unset($user['password']);
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