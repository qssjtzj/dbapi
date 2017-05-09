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

$_uid = $_SESSION["_uid_"];
$_user = $_SESSION["_user_"];

if ($_uid && $_uid>0 ) {

	$ret->rescode = 200;
        $_SESSION["_uid_"] = 0;
	$_SESSION["_user_"] = null;
        SetCookie("_uid_", 0, -1, "/", ".iyoho.mobi");
	SetCookie("_name_", "" , -1 , "/", ".iyoho.mobi");

}
echo json_encode($ret);
?>

