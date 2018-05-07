<?php

namespace jewish\backend\db_adapters;

if (!defined('_VALID_PHP')) die('Direct access to this location is not allowed.');

	class mysql {

		public  $errors;
		public	$queries;
		public	$use_cashe;
		public	$settings;
		private $cashed;
		private $connection;

		function __construct($settings, $select_db=true) {
			$this->settings = $settings;
			/*$this->connection = mysql_connect($settings['host'], $settings['user'], $settings['password']);
			$this->run("SET NAMES 'utf8'");
			if ((!empty($settings['name']))&&($select_db)) {$this->run("USE `{$settings['name']}`");}*/
			$this->connect($select_db);
			$this->errors = array();
			$this->use_cashe = true;
			$this->cashed = array();
		}


	/* * * PRIVATE FUNCTIONS * * */

		private function connect($select_db=true) {
			$this->connection = mysql_connect($this->settings['host'], $this->settings['user'], $this->settings['password']);
			if (is_resource($this->connection)) {
				$this->run("SET NAMES 'utf8'");
				if (!empty($this->settings['name']) && $select_db) {$this->run("USE `{$this->settings['name']}`");}
				return true;
			} else {
				return false;
			}
		}

		private function isPathCorrect($conds, $path='') {
			if (empty($conds['table'])) {
				$this->errors[] = array(
					'code' => 5002,
					'time' => microtime(true),
					'message' => 'Mysql class: Invalid path: the table parameter is corrupted'.(empty($path)? '': (' in given string ('.$path.')'))
				);
				return false;
			}
			return true;
		}

		private function explain($path) { /* extracts query params from input string */
			if (!empty($path)) {
				preg_match('/^((\w+)\.|)(\w+)(\{.+\}|)([\#\S*]*) *(.*)$/', $path, $matches);
				if (!isset($matches[0])) {
					return false;
				}
				$params = array();
				if (empty($matches[2])) {
					if (!empty($this->settings['name'])) {
						$params['database'] = $this->settings['name'];
					}
				} else {
					$params['database'] = $matches[2];
				}
				$params['table'] = $matches[3];
				if (!empty($matches[4])) {
					$matches[4] = substr($matches[4], 1, (strlen($matches[4])-2));
					$params['columns'] = explode(',', $matches[5]);
				}
				if (!empty($matches[5])) {
					$params['keys'] = substr($matches[5], 1);
				}
				if (!empty($matches[6])) {
					$additional = array();
					$additional = explode(' ', $matches[6]);
					foreach ($additional as $value) {
						if (!empty($value)) {
							switch (substr($value, 0, 1)) {
								case '[':
									$transport = substr($value, 1, strlen($value)-2);
									$transport = explode('-', $transport);
									if (count($transport)>1) {
										$params['limit']['total'] = abs($transport[1]-$transport[0]);
										$params['limit']['start'] = $transport[0];
									} else {
										$params['limit']['total'] = $transport[0];
									}
								break;
								case '(':
									$params['filter'] = $value;
								break;
								case '<':
									$transport = substr($value, 1, strlen($value)-2);
									$params['ordering'] = $transport;
								break;
								case '.':
									$params['columns'][] = substr($value, 1);
								break;
							}
						}
					}
				}
				return $params;
			} else {
				return false;
			}
		}

		private function prepare($conditions, $for='read') { /* prepares sql-query from params array */
			if (!$conditions) {return false;}
			switch ($for) {
				case 'write': /* * * ADDING DATA * * */
					$subject = $conditions[0];
					$data = $conditions[1];
					if ($this->use_cashe) {
						$cashe_key = md5($subject['database'].'.'.$subject['table']);
						if (isset($this->cashed[$cashe_key]['fields'])) {
							$fieldlist = $this->cashed[$cashe_key]['fields'];
						} else {
							$fieldlist = $this->list_fields($subject['database'], $subject['table']);
							$this->cashed[$cashe_key]['fields'] = $fieldlist;
						}
					} else {
						$fieldlist = $this->list_fields($subject['database'], $subject['table']);
					}
					if (!is_array($data)) {
						$cropped = explode(',',$data);
						$data = array();
						$i = 0;
						while (isset($cropped[$i])) {//we can send string like this 'val1,val2,val3'
							//and this will generate query with values by given order
							// indexing [$i+1] skips `id` field in began
							$entry = explode('=', $cropped[$i]);
							switch (count($entry)) {
								case 0:
									$data[$fieldlist[$i+1]['Field']] = 'NULL';
								break;
								case 1:
									if (substr($entry[0], 0, 1)=="'") {
										$entry[0] = substr($entry[0], 1, strlen($entry[0])-2);
									}
									$data[$fieldlist[$i+1]['Field']] = $entry[0];
								break;
								case 2:
									if (substr($entry[1], 0, 1)=="'") {
										$entry[1] = substr($entry[1], 1, strlen($entry[1])-2);
									}
									$data[$entry[0]] = $entry[1];
								break;
							}
							$i++;
						}
					}
					$attributas = '';
					$cortejias = '';
					$i = 0;
					while (isset($fieldlist[$i]['Field'])) {
						$attributas.='`'.$fieldlist[$i]['Field'].'`';
						if (isset($data[$fieldlist[$i]['Field']])) {
							$val = $data[$fieldlist[$i]['Field']];
							if ($val==='NULL') {
								$cortejias.='NULL';
							} else {
								$val = (is_scalar($val)? mysql_real_escape_string($val): gettype($val));
								$cortejias.="'".$val."'";
							}
						} else {
							$cortejias.='NULL';
						}
						$i++;
						if (isset($fieldlist[$i]['Field'])) {
							$attributas.=',';
							$cortejias.=',';
						}
					}
					$sql = 'INSERT INTO `'.$subject['database'].'`.`'.$subject['table'].'` ('.$attributas.') VALUES ('.$cortejias.')';
				break;
				case 'modify': /* * * MODING DATA * * */
					$subject = $conditions[0];
					$data = $conditions[1];
					$fresh = array();
					if (!is_array($data)) {
						$this->errors[] = array(
							'code' => 5001,
							'time' => microtime(true),
							'message' => 'Mysql class: Invalid argument: mod() function second argument must be array'
						);
						return false;
					}
					foreach ($data as $field=>$value) {
						$value = ((($value==='NULL') || is_null($value))? 'NULL': ("'".(is_scalar($value)? mysql_real_escape_string($value): gettype($value))."'"));
						$fresh[] = '`'.$field.'`='.$value;
					}
					$data = implode(',', $fresh);
					if (!empty($subject['filter'])) {
						$where = $this->translate_condition($subject['filter']);
						$where = ' WHERE '.$where;
					} else {
						$where = '';
					}
					if (!empty($subject['keys'])) {
						$entries = array(
							'/^([\d\,]+)$/',
							'/^(\d+)\-+(\d+)$/'
						);
						$modyfications = array(
							'`id` IN ($1)',
							'`id` BETWEEN $1 AND $2'
						);
						$concretization = preg_replace($entries, $modyfications, $subject['keys']);
						if (!empty($where)) {
							$where.=' AND '.$concretization;
						} else {
							$where.=' WHERE '.$concretization;
						}
						if (is_int($subject['keys'])) {
							$limit = ' LIMIT 1';
						}
					}
					$sql = "UPDATE `".$subject['database']."`.`".$subject['table']."` SET ".$data.$where;
					if (!empty($limit)) {
						$sql.=$limit;
					}
				break;
				default: /* * * READING DATA * * */
					$from = '`'.$conditions['database'].'`.`'.$conditions['table'].'`';
					if (isset($conditions['keys'])&&(empty($conditions['keys']))) {
						if (empty($conditions['columns'][0])) {
							$fields = 'COUNT(*)';
						} else {
							$fields = 'COUNT(`'.$conditions['columns'][0].'`)';
						}
					} else {
						if (!empty($conditions['columns'])) {
							if (is_array($conditions['columns'])) {
								$fields = '`'.implode('`,`', $conditions['columns']).'`';
							} else {
								$fields = $conditions['columns'];
							}
						} else {
							$fields = '*';
						}
					}
					if (!empty($orderby)) {
						$orderby = ' ORDER BY '.$orderby;
					} else {
						$orderby = ' ';
					}
					if (!empty($conditions['filter'])) {
						$where = $this->translate_condition($conditions['filter']);
						$where = ' WHERE '.$where;
					} else {
						$where='';
					}
					if (!empty($conditions['keys'])) {
						$entries = array(
							'/^([\d\,]+)$/',
							'/^(\d+)\-+(\d+)$/'
						);
						$modyfications = array(
							'`id` IN ($1)',
							'`id` BETWEEN $1 AND $2'
						);
						$concretization = preg_replace($entries, $modyfications, $conditions['keys']);
						if (!empty($where)) {
							$where.=' AND '.$concretization;
						} else {
							$where.=' WHERE '.$concretization;
						}
					}
					if (!empty($conditions['limit']['total'])) {
						if (!empty($conditions['limit']['start'])) {
							$limit = ' LIMIT '.$conditions['limit']['start'].','.$conditions['limit']['total'];
						} else {
							$limit = ' LIMIT '.$conditions['limit']['total'];
						}
					} else {
						$limit = '';
					}
					$sql = 'SELECT '.$fields.' FROM '.$from.$where.$orderby.$limit;
				break;
			}
			return $sql;
		}

		private function translate_condition($where) {
			$entries = array(
				'/(?<=\(|\&|\|) *(\w+) */',
				'/(?<=(?<!\<|\>)\=)(?<!\<|\>) *(\w+) */'
			);
			$modyfications = array(
				'`$1`',
				'\'$1\''
			);
			$where = preg_replace($entries, $modyfications, $where);
			$replacants = array(
				'&&' => ' AND ',
				'||' => ' OR '
			);
			$where = strtr($where, $replacants);
			return $where;
		}


	/* * * PUBLIC FUNCTIONS * * */

		public function extract($mysql_result, $serialize=false) {
			if (empty($mysql_result)) {return false;}
			$data = false;
			$cols = @mysql_num_fields($mysql_result);
			$rows = @mysql_num_rows($mysql_result);
			if ((!$cols) && (!$serialize)) {
				$data = false;
			} else if ((($cols===1) && ($rows>1)) && (!$serialize)) {
				$i = 0;
				while ($i<$rows) {
					$data[$i] = mysql_result($mysql_result, $i);
					$i++;
				}
			} else if (($rows===1) && ($cols>1) && (!$serialize)) {
				$data = mysql_fetch_assoc($mysql_result);
			} else if (($rows===1) && ($cols===1) && (!$serialize)) {
				$data = mysql_result($mysql_result, 0, 0);
			} else {
				if (($serialize) && (!$cols)) {
					$data = array();
				} else {
					while ($data_arr = mysql_fetch_assoc($mysql_result)) {
						$data[] = $data_arr;
					}
				}
			}
			return $data;
		}

		public function ask($sql) {
			return $this->extract($this->run($sql), true);
		}

		public function to_assoc($res) {
			return @mysql_fetch_assoc($res);
		}

		public function is_result_empty($res) {
			return !@mysql_num_rows($res);
		}

		public function num_rows($res) {
			return intval(@mysql_num_rows($res));
		}

		public function list_fields($db, $tbl) {
			return $this->extract($this->run('SHOW COLUMNS FROM `'.$db.'`.`'.$tbl.'`'));
		}

		public function clear_cashe($target='') {
			if (empty($target)) {
				$this->cashed = array();
			} else {
				unset($this->cashed[md5($target)]);
			}
		}

		public function has_table($tbl) {
			$exists = $this->run('SHOW COLUMNS FROM '.$tbl);
			return !empty($exists);
		}

		public function escape($str) {
			return mysql_real_escape_string($str, $this->connection);
		}

		public function run($sql, $log=true) {
			if (empty($sql)) {return false;}
			if (!$log) {return @mysql_query($sql, $this->connection);}
			$begin = microtime(true);
			$answer = @mysql_query($sql, $this->connection);
			$end = microtime(true);
			$this->queries[] = array(
				'begin' => $begin,
				'end' => $end,
				'duration' => round(($end-$begin), 4),
				'query' => $sql
			);
			if (empty($answer)) {
				$errno = mysql_errno($this->connection);
				$this->errors[] = array(
					'code' => $errno,
					'time' => $end,
					'message' => mysql_error($this->connection)
				);
				if ($errno=='2006') {
					mysql_close($this->connection);
					$this->connection = 0;
					$connected = $this->connect();
					if ($connected) {
						return mysql_query($sql, $this->connection);
					}
				}
			}
			return $answer;
		}

		private function multiQueryShellExec($fname) {
			$script_path = substr($_SERVER['SCRIPT_FILENAME'], 0, strrpos($_SERVER['SCRIPT_FILENAME'], '/')+1);
			$command = 'mysql'
				.' --host='.$this->settings['host']
				.' --user='.$this->settings['user']
				.' --password='.$this->settings['password']
				.' --database='.$this->settings['name']
				.' --execute="SOURCE '.$script_path.$fname.'"';
			$begin = microtime(true);
			$output = shell_exec($command);
			$end = microtime(true);
			$this->queries[] = array(
				'begin' => $begin,
				'end' => $end,
				'duration' => round(($end-$begin), 4),
				'query' => "Shell command executed:\n{$command}\n\nOutput:\n{$output}\n\n"
			);
			return !is_null($output);
		}

		private function multiQueryFileSplitSQL($fname, $delimiter=';') {
			@set_time_limit(0);
			if (is_file($fname)) {
				$r = fopen($fname, 'r');
				if (is_resource($r)) {
					$query = array();
					while (!feof($r)) {
						$query[] = fgets($r);
						if (preg_match('~'.preg_quote($delimiter, '~').'\s*$~iS', end($query))===1) {
							$query = trim(implode('', $query));
							$this->run($query, false);
						}
						if (is_string($query)) {$query = array();}
					}
					return fclose($r);
				}
			}
			return false;
		}

		private function multiQueryMySQLi($sql) {
			$begin = microtime(true);
			$idb = new mysqli($this->settings['host'], $this->settings['user'], $this->settings['password'], $this->settings['name']);
			$end = microtime(true);
			if (!empty($idb->connect_error) || ($idb->host_info=='')) {
				$this->errors[] = array(
					'code' => $idb->connect_errno,
					'time' => microtime(true),
					'message' => "MySQLi connection failure: {$idb->connect_error}\nnew mysqli({$this->settings['host']}, {$this->settings['user']}, {$this->settings['password']}, {$this->settings['name']})"
				);

				return false;

			} else {
				$this->queries[] = array(
					'begin' => $begin,
					'end' => $end,
					'duration' => round(($end-$begin), 4),
					'query' => "Successfully connected to MySQLi.\nHost info: {$idb->host_info}\nProtocol version: {$idb->protocol_version}\nServer info: {$idb->server_info}\nServer version: {$idb->server_version}"
				);

				$idb->query("SET NAMES 'utf8'");
				if (!empty($this->settings['name'])) {
					$idb->select_db($this->settings['name']);
				}

				$begin = microtime(true);
				$idb->multi_query($sql);
				$end = microtime(true);
				$this->queries[] = array(
					'begin' => $begin,
					'end' => $end,
					'duration' => round(($end-$begin), 4),
					'query' => 'MySQLi multi_query function executed'
				);
				if ($idb->error) {
					$this->errors[] = array(
						'code' => $idb->errno,
						'time' => $end,
						'message' => "MySQLi statement error: {$idb->errno} {$idb->error}"
					);
				}

				$idb->close();

				return true;

			}
		}

		private function clearRemarks($sql) {
			$lines = explode("\n", $sql);
			$sql = '';
			$out = '';
			foreach ($lines as $i=>$line) {
				$lines[$i] = '';
				if (empty($line)) {continue;}
				if ($line[0]=='#') {continue;}
				if (substr($line, 0, 3)=='-- ') {continue;}
				$out.=$line."\n";
			}
			return $out;
		}

		private function multiQuerySplitSQL($sql, $delimiter=';') {
			$begin = microtime(true);
			@ini_set('max_execution_time', '120');

			$sql = $this->clearRemarks($sql);

			$tokens = explode($delimiter, $sql);
			$sql = '';
			$executed = 0;
			$matches = array();
			$token_count = count($tokens);
			for ($i=0; $i<$token_count; $i++) {
				if (($i!=($token_count-1)) || (strlen($tokens[$i]>0))) {
					$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
					$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
					$unescaped_quotes = $total_quotes - $escaped_quotes;
					if (($unescaped_quotes%2)==0) {
						$this->run($tokens[$i], false);
						$executed++;
						$tokens[$i] = '';
					} else {
						$temp = $tokens[$i].$delimiter;
						$tokens[$i] = '';
						$complete_stmt = false;
						for ($j=$i+1; (!$complete_stmt && ($j<$token_count)); $j++) {
							$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
							$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);
							$unescaped_quotes = $total_quotes-$escaped_quotes;
							if (($unescaped_quotes%2)==1) {
								$this->run($temp.$tokens[$j], false);
								$executed++;
								$tokens[$j] = '';
								$temp = '';
								$complete_stmt = true;
								$i = $j;
							} else {
								$temp.=$tokens[$j].$delimiter;
								$tokens[$j] = '';
							}
						}
					}
				}
			}

			$end = microtime(true);
			$this->queries[] = array(
				'begin' => $begin,
				'end' => $end,
				'duration' => round(($end-$begin), 4),
				'query' => "Given list of queries was parsed and performed using mysql::multiQuerySplitSQL() function.\nExecuted queries: {$executed}."
			);

			return $executed;
		}

		public function multi_query($sql, $force_parsing=false) {
			// if mysqli installed, run multi_query
			// else (or if forced) parse sql with php and perform single queries

			if (extension_loaded('mysqli') && function_exists('mysqli_connect') && !$force_parsing) {
				return $this->multiQueryMySQLi($sql);
			} else {
				return $this->multiQuerySplitSQL($sql);
			}
		}

		public function import_sql_file($fname, $deny_shell=false) {
			// if not safe_mode, run cmd import $ mysql -u username -p -h localhost dbname < dumpfile.sql
			// else (or if forced) read file contents and perform multi_query

			if (is_file($fname) && (substr($fname, -4)=='.sql')) {
				$safe_mode = @ini_get('safe_mode');
				if (!$safe_mode && !$deny_shell) {
					return $this->multiQueryShellExec($fname);
				} else {
					$force_parsing = (filesize($fname)>=(1024*1024));
					return $this->multi_query(@file_get_contents($fname), $force_parsing);
				}
			}

			return false;
		}

		public function get($path) {
			$conditions = $this->explain($path);
			if (!$this->isPathCorrect($subject, $path)) {return false;}
			$question = $this->prepare($conditions);
			$answer = $this->run($question);
			$data = $this->extract($answer);
			return $data;
		}

		public function add($path, $data, $return_id=true) {
			$subject = $this->explain($path);
			if (!$this->isPathCorrect($subject, $path)) {return false;}
			$sql = $this->prepare(array($subject, $data), 'write');
			$added = $this->run($sql);
			return ($return_id? ($added? $this->extract($this->run("SELECT LAST_INSERT_ID()")): 0): true);
		}

		public function mod($path, $data, $return='BOOLEAN') {
			$subject = $this->explain($path);
			if (!$this->isPathCorrect($subject, $path)) {return false;}
			$sql = $this->prepare(array($subject, $data), 'modify');
			$updated = $this->run($sql);
			if ($return=='BOOLEAN') {
				return $updated;
			} else if ($return=='AFFECTED') {
				if ($updated) {
					$affected = mysql_affected_rows($this->connection);
					if ($affected>0) {return $affected;}
				}
				return 0;
			} else if ($return=='DATA') {
				return $this->get($path);
			} else {
				return true;
			}
		}

		public function select($col, $tbl='', $flt='', $ord='', $fr='', $num='') { // backward compatibility
			return $this->run("SELECT {$col}".(empty($tbl)? '': " FROM {$tbl}").(empty($flt)? '': " WHERE {$flt}").(empty($ord)? '': " ORDER BY {$ord}").(empty($num)? '': (" LIMIT ".(($fr>0)? "{$fr}, ": '')."{$num}")));
		}
	}
// UTF-8 forced [üёξ]
// version 1.3.123
// last modified 2013.04.17
?>