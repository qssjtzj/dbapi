<?php
#error_reporting(E_ALL);
session_start();

$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

@header("Access-Control-Allow-Origin:".$origin);
@header("Access-Control-Allow-Credentials:true");

define('FILEPATH',dirname(__FILE__));
require_once(FILEPATH.'/include/user_info.php');

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['account']) || !isset($_GET['passwd']) ||!isset($_GET['code'])) {
        $ret->error= "account, passwd, code  must be exist";
        echo json_encode($ret);
        exit();
}


$uid = 0; //getint($_GET, 'uid');
$_account = strtolower($_GET['account']);
$_passwd = $_GET['passwd'];

$_code = md5(strtolower($_GET['code']));
if($_code != $_SESSION["verification"]){ //$_SESSION["verification"]
    $ret->error= "code error" . $_code . $_SESSION["verification"];
    echo json_encode($ret);
    exit();    
}

#$passwd = checkstr($_GET, 'passwd');

$result = null;
if ($_passwd !== '' && $_account !== '') {
	$userinfo = new UserInfo();
	$result = $userinfo->check_passwd($_account, $_passwd);
	$uid = $result[0]['id'];
}

#$ret->uid = $uid;


if ($result != null ) {
	$ret->uid = $result[0]['id'];
	$ret->username = $result[0]['nick'];

	$ret->rescode = 200;
        $_SESSION["_uid_"] = $uid;
	$_SESSION["_user_"] = $result;
        SetCookie("_uid_", $uid, time() + 7200* 7, "/", ".iyoho.mobi");
	SetCookie("_name_", $ret->username , time() + 7200*7, "/", ".iyoho.mobi");

}
echo json_encode($ret);
?>

