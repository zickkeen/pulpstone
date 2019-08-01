<?php
session_start();
$show="home";
exec('grep user /root/passwd |awk -F" " \'{print $2}\'',$user);
exec('grep passwd /root/passwd |awk -F" " \'{print $2}\'',$pass);
$snowcolor = dechex(rand(0x000000, 0xFFFFFF));
 
if ($_GET['login']) {

     if ($_POST['username'] == $user[0]
         && $_POST['password'] == $pass[0]) {
 
         $_SESSION['loggedin'] = 1;
 
         header("Location: status.php");
         exit;

     } else 
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		$ip = $_SERVER['REMOTE_ADDR'];
		}
		$username = "Username: ".$_POST['username']."\r\n";
		$password = "Password: ".$_POST['password']."\r\n";
		$ip = "IP: ".$ip;
		$separator = "[-------------]";
		$tanggal = "Tanggal: ".exec('date +%d-%m-%Y_%H:%M:%S',$date)."\r\n";
		$useragent = "\r\nUser Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
		exec("echo \"$tanggal$username$password$ip$useragent$separator\" >> /www/pulpstone/failed-login-attempt.log");
		echo '
			<script type="text/javascript">
				alert("Username atau Password salah!");
			</script>';
}
/*if(isset($_POST['lanjutkan'])){
	$separator = "[-------------]";
	$tanggal = "Tanggal: ".exec('date +%d-%m-%Y_%H:%M:%S',$date)."\r\n";
	$useragent = "User Agent: ".$_SERVER['HTTP_USER_AGENT']."\r\n";
 {
		echo'<script type="text/javascript">
			
			 </script>';
		exit();
	}
	$ip = "IP: ".$_SERVER['REMOTE_ADDR']."\r\n";
	exec("echo \"$tanggal$name$fb$useragent$ip$separator\" >> /www/pulpstone/failed-login-attempt.log");
}*/

	echo '
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; maximum-scale=1; minimum-scale=1;" />
<link rel="shortcut icon" type="image/png" href="favicon.png">
<title>PULPSTONE - OpenWrt</title>
<link type="text/css" rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
</head>
<body>
<div class="header" style="height:60px;">
<div class="sparator" style="margin-top:80px;">';
if ($show == "home"){
	echo'
<br>
<br>
<a href="http://pulpstone.pw" target="_blank"><img src="images/gigi-logo.png" width="auto" height="100px"></a><br>
<br>
<br>
<font face="tahoma">
<form action="?login=1" method="post">
<input type="text" autofocus name="username" size="30" placeholder="Username" style="border-radius:2px;"/>
<br>
<br>
<input type="password" name="password" size="30" placeholder="Password" style="border-radius:2px;"/>
<br>
<br>
<br>
<br>
<input type="submit" value="Login" style="width:80px;border-radius:10px;background-color:#04A4D5;"/>  
<input type="reset" value="Reset" style="width:80px;border-radius:10px;background-color:orange;"/>
</form>
</div>';
}
include 'footer.php';
?>