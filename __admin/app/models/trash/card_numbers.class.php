<?php

if (!defined("_VALID_PHP")) {die();}

class card_numbers {
	public static $db;
	private static $card_numbers_tbl = 'card_numbers';

	public static function addCardNumber() {
		global $utils, $admin_lang, $card_numbers_lang;

		$errors = array();
		if (empty($_POST['card_number']) || !preg_match('/^[0-9]{1,64}$/', @$_POST['card_number'])) {
			$errors[] = $card_numbers_lang['card_number_invalid_err'];
		} else {
			$exists_sql = "SELECT id FROM ".self::$card_numbers_tbl." WHERE card_number='{$_POST['card_number']}' LIMIT 1";
			$exists_st = self::$db->query($exists_sql);
			$exists = $exists_st->fetchAll(PDO::FETCH_ASSOC);
			$exists_st->closeCursor();
			if (isset($exists[0]['id'])) {
				$errors[] = $card_numbers_lang['card_number_exists_err'];
			}
		}

		if (count($errors)) {
			$utils->showNotice('error', $errors);
			return false;
		} else {
			$st = self::$db->query("INSERT INTO `".self::$card_numbers_tbl."` (`card_number`) VALUES ('{$_POST['card_number']}')");
			$inserted = $st->rowCount();
			$st->closeCursor();

			if ($inserted) {
				$utils->showNotice('success', $admin_lang['insert_suc']);
				$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_card_numbers&'.time(), 2000);
				return true;
			} else {
				$utils->showNotice('error', $admin_lang['insert_err']);
				return false;
			}
		}
	}

	public static function getCardNumbers() {
		$sql = "SELECT id, card_number FROM `".self::$card_numbers_tbl."` ORDER BY card_number ASC";
		$st = self::$db->query($sql);
		$numbers = $st->fetchAll(PDO::FETCH_ASSOC);

		return $numbers;
	}

	public static function delCardNumber($del_id) {
		global $utils, $admin_lang;

		if (empty($del_id)) {
			$utils->showNotice('error', $admin_lang['del_err']);
			return false;
		}
		$sql = "DELETE FROM `".self::$card_numbers_tbl."` WHERE id='".intval($del_id)."'";
		$st = self::$db->query($sql);
		$deleted = $st->rowCount();
		$st->closeCursor();
		if ($deleted) {
			$utils->showNotice('success', $admin_lang['del_suc']);
			$utils->delayedRedirect(SITE.CMS_DIR.'?page=c_card_numbers&'.time(), 2000);
			return true;
		} else {
			$utils->showNotice('error', $admin_lang['del_err']);
			return false;
		}
	}
}

?>