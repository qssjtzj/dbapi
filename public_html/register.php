<?php
session_start();

$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
@header("Access-Control-Allow-Origin:".$origin);
@header("Access-Control-Allow-Credentials:true");

define('FILEPATH',dirname(__FILE__));
require_once(FILEPATH.'/include/user_info.php');

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['account']) || !isset($_GET['passwd']) ||!isset($_GET['nick']) || !isset($_GET['verify'])) {
	$ret->error= "account, passwd, nick, verify must be exist";
	echo json_encode($ret);
	exit();
} 

$_code = md5($_GET['verify'] . "!@#$%" . $_GET['account']);

if($_code != $_SESSION["sms_code"] ){
    $ret->error= "error code " . $_code . "," . $_SESSION["sms_code"] ;
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
#$ret->uid = $uid;
if($uid) {
	#$_SESSION["_uid_"] = $uid;
	#SetCookie("_uid_", $uid, time() + 7200, "/", ".iyoho.mobi");
	$ret->rescode = 200;
} else {
	$ret->rescode = 500;
}

echo json_encode($ret);
?>
