<?php

if (!defined("_VALID_PHP")) {die();}

class sensors {
	public static $db;
	private static $sensors_tbl = 'sensors';
	private static $remote_db_settings = array(
		'host' => 'TN-SQL01\SQLEXPRESS',
		'db_name' => 'attcards',
		'db_user' => 'sa',
		'db_password' => '123'
	);
	private static $remote_db_sensors_tbl = 'Machines';

	public static function register() {
		global $utils, $admin_lang, $sensors_lang;

		//print "<pre>\n".var_export($_POST, true)."\n</pre>";
		/*array (
  'direction' => 
  array (
    10 => 'in',
    11 => 'in',
    4 => 'in',
    9 => 'out',
    5 => 'in',
    6 => 'in',
    7 => 'in',
    8 => 'in',
    12 => 'in',
    13 => 'in',
    17 => 'in',
    1 => 'in',
    2 => 'out',
    14 => 'in',
    15 => 'in',
    16 => 'in',
  ),
  'save' => 'Сохранить',
)*/
		if (is_array(@$_POST['direction']) && count(@$_POST['direction'])) {
			$gates = $_POST['direction'];
			foreach ($gates as $sensor_id=>$direction) {
				$exists_st = self::$db->query("SELECT id, direction FROM ".self::$sensors_tbl." WHERE sensor_id='".intval($sensor_id)."' LIMIT 1");
				/*print "<pre>
Sensor ID: {$sensor_id};
Direction: {$direction};

SELECT id FROM ".self::$sensors_tbl." WHERE sensor_id='".intval($sensor_id)."' LIMIT 1</pre>";*/
				if ($exists_st) {
					$sensor_exists = $exists_st->fetchAll(PDO::FETCH_ASSOC);
					$exists_st->closeCursor();
					$sensor_exists = reset($sensor_exists);
				}
				if (isset($sensor_exists['id'])) {
					//print "<pre>Sensor exists</pre>";
					//print var_export($sensor_exists);
					if ($sensor_exists['direction']!=$direction) {
						//$upd_sql = "UPDATE `".self::$users_tbl."` SET {$upd_str} WHERE id='{$uid}' LIMIT 1";
						//$res = self::$db->query("DELETE FROM `".self::$user_role_ref_tbl."` WHERE user_id='{$uid}' AND role_id IN ('".implode("', '", $roles_deletable)."')");
						//$deleted = $res->rowCount();
						//$res->closeCursor();
						$upd_st = self::$db->query("UPDATE `".self::$sensors_tbl."` SET direction='".(($direction=='out')? 'out': 'in')."' WHERE id='{$sensor_exists['id']}'");
						//print "<pre>UPDATE `".self::$sensors_tbl."` SET direction='".(($direction=='out')? 'out': 'in')."' WHERE id='{$sensor_exists['id']}'</pre>";
						//if (!$upd_st) {var_export(self::$db->errorinfo());}
						$upd_st->closeCursor();
					}
				} else {
					$ins_st = self::$db->query("INSERT INTO `".self::$sensors_tbl."` (`id`, `sensor_id`, `direction`) VALUES (NULL, '{$sensor_id}', '".(($direction=='out')? 'out': 'in')."')");
					//print "<pre>INSERT INTO (`id`, `sensor_id`, `direction`) VALUES (NULL, '{$sensor_id}', '".(($direction=='out')? 'out': 'in')."')</pre>";
					if (!$ins_st) {var_export(self::$db->errorinfo());}
					$ins_st->closeCursor();
				}
			}
		}
		$utils->showNotice('success', $admin_lang['update_suc']);

		return true;
	}

	public static function getSensorsRemote() {
		global $utils, $admin_lang, $sensors_lang;

		$sensors = array();
		$ms_sql_db = new PDO(
			"sqlsrv:Server=".self::$remote_db_settings['host'].";Database=".self::$remote_db_settings['db_name'],
			self::$remote_db_settings['db_user'],
			self::$remote_db_settings['db_password']
		);
		$sql = "SELECT [ID], [MachineAlias], [IP], [MachineNumber] FROM ".self::$remote_db_sensors_tbl." ORDER BY [MachineAlias] ASC";
		$stmt = $ms_sql_db->query($sql);
		if ($stmt) {
			$sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
		} else {
			print "<pre>\n".$sql."\n</pre>";
			var_export($ms_sql_db->errorinfo());
			die();
		}

		return $sensors;
	}

	public static function getSensorsRegistered($sensors_exists=false) {
		global $utils, $admin_lang, $sensors_lang;

		$sensors = array();
		$filter = array();
		if (!empty($sensors_exists) && is_array($sensors_exists) && count($sensors_exists)) {
			$filter[] = "sensor_id IN ('".implode("', '", $sensors_exists)."')";
		}
		$where = (count($filter)? (' WHERE '.implode(' AND ', $filter)): '');
		$sql = "SELECT * FROM ".self::$sensors_tbl.$where;
		$stmt = self::$db->query($sql);
		//$sensors = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($stmt) {
			while ($sensor=$stmt->fetch(PDO::FETCH_ASSOC)) {
				$sensors[$sensor['sensor_id']] = $sensor;
			}
			$stmt->closeCursor();
		}

		return $sensors;
	}
}

?>