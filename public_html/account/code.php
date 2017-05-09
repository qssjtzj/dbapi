<?php
session_start();

$origin = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

@header("Access-Control-Allow-Origin:".$origin);
@header("Access-Control-Allow-Credentials:true");

function random($len) {
    $srcstr = "1a2s3d4f5g6hj8k9qwertyupzxcvbnmQWERTYUIOPLKJHGFDSAZXCVBNM";
    mt_srand();
    $strs = "";
    for ($i = 0;$i < $len;$i++) {
        $strs.= $srcstr[mt_rand(0, 30) ];
    }
    return $strs;
}

//随机生成的字符串
$str = random(4);
//验证码图片的宽度
$width = 160;
//验证码图片的高度
$height = 42;
//声明需要创建的图层的图片格式
@header("Content-Type:image/png");
//创建一个图层
$im = imagecreate($width, $height);
//背景色
$back = imagecolorallocate($im, 243, 251, 254);

#0xFF, 0xFF, 0xFF);
//模糊点颜色
#$pix = imagecolorallocate($im, 187, 230, 247);
//字体色
#$font = imagecolorallocate($im, 41, 163, 238);
#$black = ImageColorAllocate($im, 243, 251, 254);

//绘模糊作用的点
mt_srand();
#for ($i = 0;$i < 1000;$i++) {
#    imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $pix);

#    $randcolor = ImageColorallocate($im, rand(10, 255), rand(10, 255), rand(10, 255));
#    imagesetpixel($im, rand()%$width, rand()%$height, $randcolor);
#}

//随机的画几条线段
//for($i = 0; $i < 6; $i++)
//{
//    $lineColor = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
//    imageline ($im, rand(0,$width), 0, rand(0,$width), $height, $lineColor);
//}

$font = dirname(__FILE__).'/font/elephant.ttf';
$fontcolor = imagecolorallocate($im, mt_rand(0,156), mt_rand(0,156), mt_rand(0,156));
$str_font = ImageColorAllocate($im, 15, 96, 87);//20, 149, 136);//26, 163, 147);

for($i = 0;$i < 28;$i++){
	$fontcolor = imagecolorallocate($im, mt_rand(10, 255 ), mt_rand(10,255), mt_rand(10,255));
	imagettftext($im, 12, mt_rand(-30, 30), rand()%$width, rand()%$height,
                $fontcolor, $font, random(1) );

}

for ($i = 0;$i < strlen($str);$i++) {
	imagettftext($im, 22, mt_rand(-30, 30), ($width-40) / strlen($str) * $i  + mt_rand(20, 25), $height / 1.4, 
		$str_font, $font, $str[$i]);
}


#$body_font = ImageColorAllocate($im, 26, 163, 147);
//输出字符
#imagestring($im, 5, 7, 5, $str, $fontcolor);

//输出矩形
imagerectangle($im, 0, 0, $width - 1, $height - 1, $back);

//输出图片
imagepng($im);
imagedestroy($im);

$str = md5(strtolower($str));

//选择 cookie
//SetCookie("verification", $str, time() + 7200 , ".iyoho.mobi");
SetCookie("verification", $str, time() + 60, "/", ".iyoho.mobi");

//选择 Session
$_SESSION["verification"] = $str;
?>

