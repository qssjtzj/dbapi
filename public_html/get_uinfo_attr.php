<?php
session_start();
$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
@header("Access-Control-Allow-Origin:".$origin);
@header("Access-Control-Allow-Credentials:true");

define('FILEPATH',dirname(__FILE__));
require_once(FILEPATH.'/include/user_info.php');

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['uid']) ) {
	$ret->error= "uid must be exist";
	echo json_encode($ret);
	exit();
} 


$_uid = $_GET['uid'];
$ret->uid = $_uid;

$userinfo = new UserInfo();

$result = $userinfo->get_uinfo_addr($_uid);
$ret->result = $result;
if($result) {
	$ret->rescode = 200;
} else {
	$ret->rescode = 500;
}

echo json_encode($ret);
?>
