<?php

define('FILEPATH',dirname(__FILE__));
require_once(FILEPATH.'/include/user_info.php');

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['account'])) {
	$ret->error= "account must be exist";
	echo json_encode($ret);
	exit();
} 
$_account= $_GET['account'];

$ret->account = $_account;
$userinfo = new UserInfo();
$uid = $userinfo->get_uid_by_account($_account);
if ($uid != 0) {
	$ret->uid = (int)$uid;
	$ret->rescode = 200;
}
echo json_encode($ret);
?>
