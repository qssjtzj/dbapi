
<?php

session_start();

define('FILEPATH',dirname(__FILE__));

$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

#@header("Access-Control-Allow-Origin:http://ta.iyoho.mobi");
@header("Access-Control-Allow-Origin:".$origin);
@header("Access-Control-Allow-Credentials:true");

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['code'])) {
        $ret->error= "code must be exist";
        echo json_encode($ret);
        exit();
}

$_code = md5(strtolower($_GET['code']));
if(isset($_SESSION['verification']) 
    && $_code == $_SESSION["verification"] ){
    	
    $ret->rescode = 200;

}else{

    $ret->error= " code error :" . $_code ;
    echo json_encode($ret);
    exit();
}
echo json_encode($ret);

?>

