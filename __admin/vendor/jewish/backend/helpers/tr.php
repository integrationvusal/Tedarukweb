<?php

namespace jewish\backend\helpers;

use jewish\backend\CMS;
use jewish\backend\helpers\utils;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}

class tr {

	public static function store($data) { // 2016-05-13
		$tr_id = CMS::$db->get("SELECT `id` FROM `translates` WHERE `ref_table`=:ref_table AND `ref_id`=:ref_id AND lang=:lang AND fieldname=:fieldname LIMIT 1", [
			':ref_table' => $data['ref_table'],
			':ref_id' => $data['ref_id'],
			':lang' => $data['lang'],
			':fieldname' => $data['fieldname']
		]);
		if ($tr_id) {
			return CMS::$db->mod('translates#'.$tr_id, ['text' => $data['text']]);
		} else {
			return CMS::$db->add('translates', $data);
		}
	}

	public static function del($table, $id) {
		$params = [
			':ref_table' => $table,
			':ref_id' => $id
		];
		CMS::$db->run("DELETE FROM `translates` WHERE `ref_table`=:ref_table AND `ref_id`=:ref_id", $params);
		CMS::$db->run("OPTIMIZE TABLE `translates`");
		return true;
	}

	public static function get($table, $id) { // 2016-05-17
		$translates = [];
		foreach (CMS::$site_langs as $lng) {
			$data = CMS::$db->getPairs("SELECT `fieldname`, `text` FROM `translates` WHERE `ref_table`=:ref_table AND `ref_id`=:ref_id AND `lang`=:lang", [
				':ref_table' => $table,
				':ref_id' => $id,
				':lang' => $lng['language_dir']
			]);
			$translates[$lng['language_dir']] = $data;
		}
		return $translates;
	}

	public static function findContent($table, $id) { // 2016-06-15
		$reg = CMS::$db->getRow("SELECT * FROM content_registry WHERE ref_table=:tbl LIMIT 1", [':tbl' => $table]);
		if (empty($reg['id'])) {return false;}
		$item = CMS::$db->getRow("SELECT `c`.`id`, `t1`.`text` AS `title`, `t2`.`text` AS `text`
			FROM `{$reg['ref_table']}` `c`
				LEFT JOIN `translates` `t1` ON `t1`.`ref_table`=:ref_table AND `t1`.`ref_id`=`c`.`id` AND `t1`.`lang`=:lang AND `t1`.`fieldname`=:title_column
				LEFT JOIN `translates` `t2` ON `t2`.`ref_table`=:ref_table AND `t2`.`ref_id`=`c`.`id` AND `t2`.`lang`=:lang AND `t2`.`fieldname`=:text_column
			WHERE `c`.`id`=:id LIMIT 1",
			[
				':id' => $id,
				':ref_table' => $table,
				':lang' => CMS::$default_site_lang,
				':title_column' => $reg['title_column'],
				':text_column' => $reg['text_column']
			]
		);
		if (empty($item['id'])) {return false;}
		$item = array_merge($reg, $item);
		$item['item_link'] = strtr($item['item_link'], [
			'{ref_id}' => $item['id']
		]);
		return $item;
	}
}

?>