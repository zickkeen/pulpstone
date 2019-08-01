<?php
function ceklogin(){
    session_start();
    if (($_SESSION['loggedin'] != 1) || isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
    header("Location: login.php");
}
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
}

function css(){
global $bgUrl;
echo '
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; maximum-scale=1; minimum-scale=1;" />
<link rel="shortcut icon" type="image/png" href="favicon.png">
<title>Pulpstone OpenWrt</title>
<link type="text/css" rel="stylesheet" href="style.css" />
<link type="text/css" rel="stylesheet" href="css/style.css" />
<script type="text/javascript" src="js/jquery-2.1.3.min.js"></script>
</head>
<body>
<div class="header">
<label for="show-menu" class="show-menu">Tampilkan Menu</label>
<input type="checkbox" class="hid-nav" id="show-menu" role="button">
	<ul id="menu" class="nav">
		<li><a href="#">Modem</a>
			<ul class="hidden">
			<li><a href="status.php">Status</a></li>
			<li><a href="ussd.php">USSD</a></li>
			<li><a href="sms.php">SMS</a></li>
			<li><a href="profile.php">Profile</a></li>
			</ul>
		</li>
		<li><a href="#">Multimedia</a>
			<ul class="hidden">
			<li><a href="mp3.php">Mp3 Player</a></li>
			<li><a href="video.php">Video Player</a></li>
			</ul>
		</li>
		<li><a href="#">Miscellaneous</a>
			<ul class="hidden">
			<li><a href="vpn.php" ">VPN Accounts</a></li>
			<li><a href="log.php" ">Log</a></li>
			<li><a href="wifi.php">WIFI</a></li>
			<li><a href="ch_pass.php" ">Password</a></li>
			<li><a href="../cgi-bin/pingloop">Ping Loop v3</a></li>
			</ul>
		<li><a href="#">My Apps</a>
			<ul class="hidden">
			<li><a href="http://192.168.2.5:81" target="_blank" ">3G/4G Info</a></li>
			<li><a href="wget.php" ">WgetUI</a></li>
			<li><a href="terminal.php" ">Terminal</a></li>
			<li><a href="http://192.168.2.5:8080" target="_blank" ">OSCam</a></li>
			<li><a href="http://192.168.2.5:8291" target="_blank" ">MyCam</a></li>
			<li><a href="http://192.168.2.5:9091" target="_blank" ">Transmission</a></li>
			</ul>
		</li>
		<li><a href="../cgi-bin/luci" target="_blank" ">LuCI</a></li>
		<li><a href="http://pulpstone.pw" target="_blank" ">Pulpstone</a></li>
		<li><a href="about.php" ">About</a></li>
</ul>

</div>
<div class="img-header">
<a href="http://pulpstone.pw" target="_blank"><img src="images/pulpstone.png" width="250px" height="auto"></a>
</div>
<div class="main-box">';
}
?>
