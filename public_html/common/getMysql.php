<?php

define('MYSQL_PATH',dirname(__FILE__));
require_once(MYSQL_PATH.'/mysql.conf.php');
#require_once(MYSQL_PATH.'/mysql.class.php');
require_once(MYSQL_PATH.'/pdo.class.php');

function getMysql($location='') {
	$confs = NULL;

	
	if ($location === 'iyoho_master'){
		global $iyoho_master; 
		$confs = $iyoho_master;
	}
	if ($confs == NULL) {
		return FALSE;
	}
	$num = count($confs);
	$conf = FALSE;
	if ($num > 1) {
		$random = rand(0, $num - 1);	
		$conf = $confs[$random];
	} else if ($num === 1) {
		$conf = $confs[0];
	}

/*	if ($conf) {

var_dump("______________________\n");

		$db = new mysql_db();
		$db->setConfig($conf['host'], $conf['user'], $conf['password'], $conf['dbname']);
		$db->query("SET NAMES utf8"); 

		var_dump($db);
		return $db;
	}

*/



	if ($conf) {
		
    		$db = proPdo::getInstance();
    		$db->setConfig($conf['host'], $conf['user'], $conf['password'], $conf['dbname']);
    		return $db;
    	}


	return NULL;
}

?>
