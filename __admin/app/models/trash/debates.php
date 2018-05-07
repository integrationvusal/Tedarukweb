<?php

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class debates {
	private static $runtime = [];

	public static $curr_pg = 1;
	public static $pp = 20;
	public static $pages_amount = 0;
	public static $items_amount = 0;
	public static $tr_fields = ['title', 'text', 'is_published_lang'];


	private static function processSef(&$response, &$poll, $item_id='') { // 2016-06-01
		$title = trim(@$_POST['title'][CMS::$default_site_lang]);
		$sef = trim(@$_POST['sef']);
		if (empty($sef) && !empty($title)) {
			$sef = $title;
		}
		$sef = utils::makeSEF($sef);
		if (empty($sef) && !empty($item_id)) {
			$response['errors'][] = 'poll_edit_err_sef_empty';
		} else {
			$poll['sef'] = $sef;
		}
	}

	private static function processTerm(&$response, &$poll, $item_id='') { // 2016-06-01
		if (!empty($item_id)) {
			$poll['start_date'] = null;
			$poll['finish_date'] = null;
		}
		if (!empty($_POST['start_date'])) {
			if (utils::valid_date($_POST['start_date']) && (utils::formatPlainDate('d.m.Y', $_POST['start_date'])==$_POST['start_date'])) {
				$poll['start_date'] = utils::formatPlainDate('Y-m-d', $_POST['start_date']);
			} else {
				$response['errors'][] = 'poll_edit_err_start_date_invalid';
			}
		}

		if (!empty($_POST['finish_date'])) {
			if (utils::valid_date($_POST['finish_date']) && (utils::formatPlainDate('d.m.Y', $_POST['finish_date'])==$_POST['finish_date'])) {
				$poll['finish_date'] = utils::formatPlainDate('Y-m-d', $_POST['finish_date']);
			} else {
				$response['errors'][] = 'poll_edit_err_finish_date_invalid';
			}
		}

		if (!empty($poll['start_date']) && !empty($poll['finish_date']) && ($poll['start_date']>=$poll['finish_date'])) {
			$response['errors'][] = 'poll_edit_err_term_invalid';
		}
	}

	private static function processTranslates(&$response, &$translates) { // 2016-05-31
		foreach (CMS::$site_langs as $lng) {
			foreach (self::$tr_fields as $f) {
				if (in_array($f, ['title', 'text'])) {
					$translates[$lng['language_dir']][$f] = trim(@$_POST[$f][$lng['language_dir']]);

					if (($lng['language_dir']==CMS::$default_site_lang) || !empty($_POST['is_published_lang'][$lng['language_dir']])) {
						if (empty($translates[$lng['language_dir']][$f]) && in_array($f, ['title'])) {
							$response['errors'][] = 'poll_edit_err_'.$f.'_empty';
						}
					}
				} else if (in_array($f, ['is_published_lang'])) {
					if ($lng['language_dir']==CMS::$default_site_lang) {
						$translates[$lng['language_dir']][$f] = '1';
					} else {
						$translates[$lng['language_dir']][$f] = (empty($_POST[$f][$lng['language_dir']])? '0': '1');
					}
				}
			}
		}
	}


	public static function getPollsList() { // 2016-05-31
		$list = [];

		$joins = [];
		$joins['tr'] = "LEFT JOIN translates tr ON tr.ref_table='debates' AND tr.ref_id=p.id AND tr.lang=:default_site_lang AND tr.fieldname='title'";
		$joins['cu'] = "LEFT JOIN cms_users cu ON cu.id=p.add_by";
		$filter = [];
		$filter[] = "p.is_deleted='0'";
		if (!empty($_GET['q'])) {
			$filter[] = "tr.text LIKE '%".utils::makeSearchable($_GET['q'])."%'";
		}
		if (in_array(@$_GET['filter']['status'], ['0', '1'])) {
			$filter[] = "p.is_published=".CMS::$db->escape($_GET['filter']['status']);
		}
		if (!empty($_GET['filter']['author'])) {
			$filter[] = "p.add_by='".(int)$_GET['filter']['author']."'";
		}
		$where = (empty($filter)? '': ('WHERE '.implode(" AND ", $filter)));

		$c = CMS::$db->get("SELECT COUNT(p.id)
			FROM debates p
				".implode("\n", $joins)."
			{$where}", [
			':default_site_lang' => CMS::$default_site_lang
		]);
		self::$items_amount = $c;
		//print "<pre>RESULT:\n{$c}\n\nQUERIES:\n".var_export(CMS::$db->queries, 1)."\n\nERRORS:\n".var_export(CMS::$db->errors, 1)."\n</pre>";
		$pages_amount = ceil($c/self::$pp);

		if ($pages_amount>0) {
			self::$pages_amount = $pages_amount;
			self::$curr_pg = ((self::$curr_pg>self::$pages_amount)? self::$pages_amount: self::$curr_pg);
			$start_from = (self::$curr_pg-1)*self::$pp;

			$list = CMS::$db->getAll("SELECT p.id, p.sef, p.start_date, p.finish_date, p.add_by, p.add_datetime, p.mod_by, p.mod_datetime, p.is_published,
					tr.text AS title,
					cu.name AS author_name
				FROM debates p
					".implode("\n", $joins)."
				{$where}
				ORDER BY p.id DESC
				LIMIT ".(($start_from>0)? ($start_from.', '): '').self::$pp, [
				':default_site_lang' => CMS::$default_site_lang
			]);
			// print "<pre>RESULT:\n".var_export($list, 1)."\n\nQUERIES:\n".var_export(CMS::$db->queries, 1)."\n\nERRORS:\n".var_export(CMS::$db->errors, 1)."\n</pre>";
		}

		return $list;
	}

	public static function getAuthors() { // 2016-05-31
		$sql = "SELECT cu.id, cu.name, cu.role
			FROM cms_users cu
				JOIN debates p ON p.add_by=cu.id
			WHERE cu.role IN ('".implode("', '", array_keys(CMS::$roles))."') AND p.is_deleted='0'
			GROUP BY cu.id
			ORDER BY cu.name ASC";
		return CMS::$db->getAll($sql);
	}

	public static function addPoll() { // 2016-05-31
		$response = ['success' => false, 'message' => 'insert_err'];

		$poll = [];
		$translates = [];

		self::processSef($response, $poll);

		self::processTerm($response, $poll);

		self::processTranslates($response, $translates);

		//$response['errors'][] = 'prevent saving';
		if (empty($response['errors'])) {
			$poll['is_published'] = (empty($_POST['is_published'])? '0': '1');
			$poll['add_by'] = $_SESSION[CMS::$sess_hash]['ses_adm_id'];
			$poll['add_datetime'] = date('Y-m-d H:i:s');

			$poll_id = CMS::$db->add('debates', $poll);

			if ($poll_id) {
				$response['success'] = true;
				$response['message'] = 'insert_suc';

				// saving translates
				foreach ($translates as $lang=>$tr_data) {
					foreach ($tr_data as $fieldname=>$text) {
						tr::store([
							'ref_table' => 'debates',
							'ref_id' => $poll_id,
							'lang' => $lang,
							'fieldname' => $fieldname,
							'text' => $text,
						]);
					}
				}

				// creating counters
				$types = ['up_vote', 'down_vote', 'neutral_vote', 'comment'];
				foreach ($types as $e) {
					CMS::$db->add('counters', [
						'ref_table' => 'debates',
						'ref_id' => $poll_id,
						'type' => $e,
						'counter' => '0',
					]);
				}

				// log event
				CMS::log([
					'subj_table' => 'debates',
					'subj_id' => $poll_id,
					'action' => 'add',
					'descr' => 'Poll added by admin '.ADMIN_INFO,
				]);
			}
		}

		return $response;
	}

	public static function getPoll($poll_id) { // 2016-06-01
		$sql = "SELECT * FROM debates WHERE id=:poll_id AND is_deleted='0' LIMIT 1";
		$poll = CMS::$db->getRow($sql, [
			':poll_id' => $poll_id
		]);
		if (!empty($poll['id'])) {$poll['translates'] = tr::get('debates', $poll_id);}
		return $poll;
	}

	public static function editPoll($id) { // 2016-06-01
		$response = ['success' => false, 'message' => 'update_err'];
		$poll = self::getPoll($id);
		if (empty($poll['id'])) {
			$response['message'] = 'poll_edit_err_not_found';
			return $response;
		}

		$upd = [];
		$translates = [];

		self::processSef($response, $upd, $id);

		self::processTerm($response, $upd, $id);

		self::processTranslates($response, $translates);

		if (empty($response['errors'])) {
			$upd['is_published'] = (empty($_POST['is_published'])? '0': '1');
			$upd['mod_by'] = $_SESSION[CMS::$sess_hash]['ses_adm_id'];
			$upd['mod_datetime'] = date('Y-m-d H:i:s');

			$updated = CMS::$db->mod('debates#'.(int)$id, $upd);

			// saving translates
			foreach ($translates as $lang=>$tr_data) {
				foreach ($tr_data as $fieldname=>$text) {
					tr::store([
						'ref_table' => 'debates',
						'ref_id' => $id,
						'lang' => $lang,
						'fieldname' => $fieldname,
						'text' => $text,
					]);
				}
			}

			// log event
			CMS::log([
				'subj_table' => 'debates',
				'subj_id' => $id,
				'action' => 'edit',
				'descr' => 'Poll modified by admin '.ADMIN_INFO,
			]);

			$response['success'] = true;
			$response['message'] = 'update_suc';
		}

		return $response;
	}

	public static function setPollStatus($id, $status) { // 2016-06-01
		$updated = CMS::$db->mod('debates#'.(int)$id, [
			'is_published' => (($status=='allow')? '1': '0')
		]);

		if ($updated) {
			CMS::log([
				'subj_table' => 'debates',
				'subj_id' => $id,
				'action' => 'edit',
				'descr' => 'Poll '.(($status=='allow')? '': 'un').'published by admin '.ADMIN_INFO,
			]);
		}

		return $updated;
	}

	public static function deletePoll($id) {
		$deleted = CMS::$db->mod('debates#'.(int)$id, [
			'is_deleted' => '1',
		]);

		if ($deleted) {
			CMS::log([
				'subj_table' => 'debates',
				'subj_id' => $id,
				'action' => 'delete',
				'descr' => 'Poll moved to recycle bin by admin '.ADMIN_INFO,
			]);
		}

		return $deleted;
	}
}

?>