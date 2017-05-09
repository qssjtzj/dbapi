<?php
session_start();

define('FILEPATH',dirname(__FILE__));
require_once(FILEPATH.'/include/user_info.php');

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['account']) || !isset($_GET['passwd']) ||!isset($_GET['nick']) ) {
        $ret->error= "account, passwd, nick must be exist";
        echo json_encode($ret);
        exit();
}

$_account = strtolower($_GET['account']);
$_passwd = $_GET['passwd'];
$ret->account = $_account;

$userinfo = new UserInfo();
$register_info = array("nick",
                        "passwd",
                        "account") ;
$params = array();

foreach($register_info as $value) {
	if (isset($_GET[$value])) {
		$params[$value] = $_GET[$value];
	}
}

$uid = $userinfo->register_user($_account, $params);
$ret->uid = $uid;
if($uid) {
		$ret->rescode = 200;
} else {
		$ret->rescode = 500;
}

echo json_encode($ret);
?>
