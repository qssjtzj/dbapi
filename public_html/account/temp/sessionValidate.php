<?php
session_start();
session_destroy();
?>
<html>
<head>
<title>session ͼƬ��֤ʵ��</title>
<style type="text/css">
#login p{
margin-top: 15px;
line-height: 20px;
font-size: 14px;
font-weight: bold;
}
#login img{
cursor:pointer;
}
form{
margin-left:20px;
}
</style>
</head> 
<body> 

<form id="login" action="" method="post">
<p>����Ϊsession��֤ʵ��</p>
<p>
<span>��֤�룺</span>
<input type="text" name="validate" value="" size=10> 
<img  title="���ˢ��" src="./captcha.php" align="absbottom" onclick="this.src='captcha.php?'+Math.random();"></img>
</p>
<p>
<input type="submit">
</p>
</form>
<?php
$validate="";
if(isset($_POST["validate"])){
$validate=$_POST["validate"];
echo "���ղ�������ǣ�".$_POST["validate"]."<br>״̬��";
if($validate!=$_SESSION["authnum_session"]){
echo "<font color=red>��������</font>"; 
}else{
echo "<font color=green>ͨ����֤</font>"; 
}
} 
?>
