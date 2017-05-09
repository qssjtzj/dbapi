<?php

define('DATACLIENT_BASE_PATH',dirname(__FILE__));

require_once(DATACLIENT_BASE_PATH.'/getMysql.php');

class DataClient {
	private static $mysql = array();

	public function getMysqlClient($conf = "") {

		if (!is_string($conf)) {
			return FALSE;
		}

	
#		var_dump($conf . !is_string($conf) . !isset($this->mysql[$conf]));
		if (!isset($this->mysql[$conf])) {
			$this->mysql[$conf] = getMysql($conf);
		}
		return $this->mysql[$conf];
	}
		
};

?>
