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

$addr_info = array("name",
		"sex",
		"birthday",
		"age",
		"weight",
		"height",
		"email",
		"wx",
		"nationality",
		"zodiac",
		"constellation",
		"blood",
		"occupation",
		"faith",
		"marital_status",
		"address",
		"home_address",
		"smoking",
		"drinking",

		"education",
                "income",
                "work_place",
                "housing",
                "caring",
                "cooking",
                "housework",
                "married_year",
                "live_parents",

                "max_consum",
                "hobbies",
                "favorite_place",
                "favorite_food",
                "favorite_sports",
                "favorite_music",
                "mate_age",
                "mate_height",
                "mate_education",

                "mate_marital",
                "mate_work",
                "mate_work_place",
                "mate_income",
                "mate_smoking",
                "mate_drinking",
                "mate_children",
                "create_time",
                "update_time",
		) ;

$params = array();

foreach($addr_info as $value) {
	if (isset($_GET[$value])) {
		$params[$value] = $_GET[$value];
	}
}


$result = $userinfo->update_uinfo_addr($_uid, $params);
$ret->result = $result;
if($result) {
	$ret->rescode = 200;
} else {
	$ret->rescode = 500;
}

echo json_encode($ret);
?>
