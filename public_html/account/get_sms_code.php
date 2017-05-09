
<?php
session_start();

define('FILEPATH',dirname(__FILE__));

$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';  
  
#$allow_origin = array(  
#    'http://ta.iyoho.mobi',  
#    'http://dbapi.iyoho.mobi',
#    'http://www.iyoho.mobi'  
#);  
  
#if(in_array($origin, $allow_origin)){  
#    @header('Access-Control-Allow-Origin:'.$origin);       
#}
 
@header("Access-Control-Allow-Origin:".$origin);
@header("Access-Control-Allow-Credentials:true");

$ret = new stdClass();
$ret->rescode = 403;

if(!isset($_GET['phone']) ||!isset($_GET['code'])) {
        $ret->error= "phone, code  must be exist";
        echo json_encode($ret);
        exit();
}

$_phone = $_GET['phone'];
$_code = md5(strtolower($_GET['code']));

if(!isset($_SESSION["verification"]) || $_code != $_SESSION["verification"]){
    $ret->error=  "验证码错误" ;
    $ret->rescode = 1021;
    echo json_encode($ret);
    exit();
}

if( isset($_COOKIE["phone"]) ){
    $ret->error=  "请稍后再试" ;
    $ret->rescode = 1022;
    echo json_encode($ret);
    exit();
}

$str = random(4);
$sms_code = md5($str . "!@#$%" . $_phone);
$_SESSION["sms_code"] = $sms_code;
SetCookie("phone", $_phone, time() + 60, "/", ".iyoho.mobi");


if(isset($_GET['debug']) && $_GET['debug'] == 'true'){

    $ret->msg=  $str;

    $ret->rescode = 200;
    setcookie('sms', $str, time() + 60, '/', '.iyoho.mobi');
    echo json_encode($ret);
    exit();
}


header("Content-Type:text/html;charset=utf-8");
$apikey = "257e43ff8283de61934d0ddac545c309";
$mobile = $_phone;
$text = "【爱优活】您的验证码是" . $str . "。如非本人操作，请忽略本短信";

$ch = curl_init();
/* 设置验证方式 */
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept:text/plain;charset=utf-8', 'Content-Type:application/x-www-form-urlencoded', 'charset=utf-8'));
/* 设置返回结果为流 */
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
/* 设置超时时间*/
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
/* 设置通信方式 */
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// 发送短信
$data = array('text' => $text, 'apikey' => $apikey, 'mobile' => $mobile);
$json_data = send($ch, $data);
$array = json_decode($json_data, true);
#echo '<pre>';
#print_r($array);

if($array["code"] == 0){
    $ret->rescode = 200;
}

$ret->data = $array;
echo json_encode($ret);

curl_close($ch);

function send($ch, $data) {
    curl_setopt($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    return curl_exec($ch);
}

function random($len) {
    $srcstr = "0123456789";
    mt_srand();
    $strs = "";
    for ($i = 0;$i < $len;$i++) {
        $strs.= $srcstr[mt_rand(0, mb_strlen($srcstr)-1) ];
    }
    return $strs;
}

?>

