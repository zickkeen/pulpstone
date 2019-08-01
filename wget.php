<?php
include 'header.php';
ceklogin();
$show="home";
if(isset($_POST['wget-send'])){
	$speedlimit = $_POST['speed-limit'];
	if(empty($speedlimit)){
		$speedlimit="0";
	}
	$singlequote="'";
	$speedlimit=$singlequote.$speedlimit.$singlequote;
	$mode = $_POST['dl-mode'];
	if ($mode == "download-immediately"){
		$dir=$singlequote.$_POST['directory'].$singlequote;
		$dir=str_replace('/','\/',$dir);
		$tout=$singlequote.$_POST['timeout'].$singlequote;
		$maxret=$singlequote.$_POST['maxretry'].$singlequote;
		$formlink=$_POST['download-link'];
		$toSave = nl2br($_POST["download-link"]);
		$simpan = str_replace('<br />', '', $toSave);
		$filelink = fopen('/www/pulpstone/wget/wget-download-link.txt',w);
		if(!empty($dir) && empty($formlink)) {
			header( "refresh:3;url=wget.php" );
			css();
			echo "<font color=red><b>Directory or link can't be empty</b></font>";
			exit();
		}
		fwrite($filelink, $simpan. "\n");
		exec('sed -i "s,option directory.*,option directory '.$dir.',g" /etc/config/wgetui');
		exec('sed -i "s/option maxretry.*/option maxretry '.$maxret.'/g" /etc/config/wgetui');
		exec('sed -i "s/option limit.*/option limit '.$speedlimit.'/g" /etc/config/wgetui');
		exec('sed -i "s/option timeout.*/option timeout '.$tout.'/g" /etc/config/wgetui');
		exec('killall wget > /dev/null 2>&1');
		exec('/etc/init.d/wgetui stop > /dev/null 2>&1');
		exec('/etc/init.d/wgetui start > /dev/null 2>&1 &');
		usleep(500000);
		$show="log";
	}
	else {
		$starthour = $_POST['starthour'];
		$startminute = $_POST['startminute'];
		$stophour = $_POST['stophour'];
		$stopminute = $_POST['stopminute'];
		$singlequote="'";
		$dir=$singlequote.$_POST['directory'].$singlequote;
		$dir=str_replace('/','\/',$dir);
		$tout=$singlequote.$_POST['timeout'].$singlequote;
		$maxret=$singlequote.$_POST['maxretry'].$singlequote;
		$formlink=$_POST['download-link'];
		$toSave = nl2br($_POST["download-link"]);
		$simpan = str_replace('<br />', '', $toSave);
		$filelink = fopen('/www/pulpstone/wget/schedule-link.txt',w);
		if(!empty($dir) && empty($formlink)) {
			header( "refresh:3;url=wget.php" );
			css();
			echo "<font color=red><b>Directory or link can't be empty</b></font>";
			exit();
		}
		if ($starthour >= 24 or $startminute >= 60 or $stophour >= 24 or $stopminute >= 60){
			header( "refresh:3;url=wget.php" );
			css();
			echo "<font color=red><b>Time schedule is incorrect</b></font>";
			exit();
		}
		fwrite($filelink, $simpan. "\n");
		exec('sed -i "s,option directory.*,option directory '.$dir.',g" /etc/config/wgetui');
		exec('sed -i "s/option maxretry.*/option maxretry '.$maxret.'/g" /etc/config/wgetui');
		exec('sed -i "s/option limit.*/option limit '.$speedlimit.'/g" /etc/config/wgetui');
		exec('sed -i "s/option timeout.*/option timeout '.$tout.'/g" /etc/config/wgetui');
		exec("sed -i \"s/option schedule.*/option schedule 'on'/g\" /etc/config/wgetui");
		exec("sed -i 's/^.*wgetui schedule-on//g' /etc/crontabs/root");
		exec("sed -i 's/^.*wgetui schedule-off//g' /etc/crontabs/root");
		exec("sed -i '/^\s*$/d' /etc/crontabs/root");
		exec("echo '$startminute $starthour * * * wgetui schedule-on' >> /etc/crontabs/root");
		exec("echo '$stopminute $stophour * * * wgetui schedule-off' >> /etc/crontabs/root");
		exec('/etc/init.d/cron restart');
		$schedule = "<font color=blue><b>Your download will start at $starthour:$startminute and end at $stophour:$stopminute<b></font>";
	}
}
if(isset($_POST['continue-wget'])){
	exec('killall wget > /dev/null 2>&1');
	exec('/etc/init.d/wgetui stop > /dev/null 2>&1');
	exec('/etc/init.d/wgetui start > /dev/null 2>&1 &');
	usleep(500000);
	$show="log";
}
if(isset($_POST['add-queue'])){
	if(!empty($_POST['download-link'])){
		$queue=nl2br($_POST["download-link"]);
		$queue=str_replace('<br />', '', $queue);
		$file=fopen('/www/pulpstone/wget/queue.txt',a);
		fwrite($file, $queue. "\n");
		$gstatus="<font color='green'><b><center>Link(s) added to queue</center></b></font>";
		$show="home";
	}
	else{
		$gstatus="<font color='red'><b><center>Download link can't be empty</center></b></font>";
		$show="home";
	}
}
if(isset($_POST['stop-wget'])){
	exec('mv /www/pulpstone/wget/wget-download-link.txt /www/pulpstone/wget/wget-download-link.done');
	exec('/etc/init.d/wgetui stop > /dev/null 2>&1');
	exec('killall -9 wget > /dev/null 2>&1');
	exec('echo "<br><font color=black><b>Wget is not running...</b></font>" >> /www/pulpstone/wget.log');
	$show="log";
}
if(isset($_POST['delete-schedule'])){
	exec('wgetui schedule-off');
	$show="schedule-list";
}
if(isset($_POST['wget-log'])){
	$show="log";
}
if(isset($_POST['schedule-list'])){
	$show="schedule-list";
}
if(isset($_POST['back'])){
	$show="home";
}
if(isset($_POST['link-list'])){
	$show="link-list";
}
if(isset($_POST['delete-queue'])){
	unlink('wget/queue.txt');
	$delete="<b><font color='green'>Queue links have been deleted</font></b>";
	$show="link-list";
}
css();
echo '
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: "wget_status.txt",
                success: function(result) {
                    $("#status").html(result);
                }
            });
	$(document).ready(function() {
		$.ajaxSetup({ cache: false });
			});
        }, 1000);
    });
</script>';
echo '
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: "wget.log",
                success: function(result) {
                    $("#show").html(result);
                }
            });
	$(document).ready(function() {
		$.ajaxSetup({ cache: false });
			});
		var textarea = document.getElementById("show");
		textarea.scrollTop = textarea.scrollHeight;
        }, 1000);
    });
</script>';
$lines = file('/www/pulpstone/wget/wget-download-link.complete');
$lines = array_unique($lines);
file_put_contents('/www/pulpstone/wget/wget-download-link.complete', implode($lines));
exec('df -h /mnt/usb | tail -1 | awk \'{print $4}\'',$mem);
exec('uci get wgetui.setting.directory',$directory);
exec('uci get wgetui.setting.maxretry',$maxretry);
exec('uci get wgetui.setting.limit',$limit);
exec('uci get wgetui.setting.timeout',$timeout);
exec('pidof wgetui',$wgetuistatus);
exec('uci get wgetui.setting.schedule',$schedulestatus);
echo '<form action='.$_SERVER['PHP_SELF'].' method="post">';
$pl="Paste your download links here, one URL per line for multiple URLs.  
Wget WebUI supports indirect links and download queue
Download directory will be created automatically if it doesn't exist


Free Space [/mnt/usb]      : $mem[0]
           


    #WgetUI versi 25/07/2015 by Gallih#";

//================================ HOME ===========================================================================================
if ( $show == "home" ) {
	exec('date +%H',$hour);
	exec('date +%M',$minute);
	echo '
	<div class="downloads" style="text-align:left;">
	Download Directory:
			<input type="text" name="directory" size="20" value="'.$directory[0].'"><br><br>	
			Timeout:<input type="text" name="timeout" style="width:30px;" placeholder="seconds" value="'.$timeout[0].'">
			Speed Limit:<input type="text" name="speed-limit" style="width:30px;" placeholder="kBps" value="'.$limit[0].'">
			Max Retry:<input type="text" name="maxretry" style="width:30px;" value="'.$maxretry[0].'">
	<br><br>Download link (one URL per line):<br>';
	echo '
	<textarea class="scroll" name="download-link" placeholder="'.$pl.'" autofocus rows="13"></textarea>';
	echo "$gstatus";
	echo '
	<input type="radio" name="dl-mode" value="download-immediately" checked>Download immediately<br>
	<input type="radio" name="dl-mode" value="schedule-download">Schedule download<br> 
	</div><div>
	Start: <input type="text" name="starthour" size="1" value='.$hour[0].' placeholder="h"><input type="text" name="startminute" size="2" value='.$minute[0].' placeholder="m"> 
	Stop: <input type="text" name="stophour" size="1" value='.$hour[0].' placeholder="h"><input type="text" name="stopminute" size="2" value='.$minute[0].' placeholder="m"><br>
	The schedule download is in 24 hours format.<br>';
}
//================================ LOG ===========================================================================================
if ( $show == "log" ){
	exec("if [ \"$(pidof wgetui)\" ];then 
	echo \"Downloading $(grep \"Saving to: \" /www/pulpstone/wget.log | tail -n 1 | sed \"s/Saving to: //g; s/'//g\" | sed 's/[!@#\$^&*(|)]//g; s/\[//g; s/\]//g; s/^.*\///g' | head -c 30) ($(grep -E \"Length.*\(\" /www/pulpstone/wget.log | tail -n 1 | awk '{print $3}' | sed 's/(//g; s/)//g; s/\[//g; s/\]//g; s/,//g; s/K/KB/g; s/M/MB/g; s/G/GB/g' | tr -d '\n\r'))\"
	else echo 'Wget Log'; fi",$file);
	exec("if [ \"$(pidof wgetui)\" ];then 
	grep \"Saving to: \" /www/pulpstone/wget.log | tail -n 1 | sed \"s/Saving to: //g; s/'//g\" | sed 's/[!@#\$^&*(|)]//g; s/\[//g; s/\]//g; s/^.*\///g'; fi",$fullname);
	echo '<b title="'.$fullname[0].'">'.$file[0].'</b>
	<div class="ConnectionBox">
		<code>
		<div id="show" style="font-size: 9px;  word-wrap: break-word; white-space: pre-wrap; word-break: break-all; max-width:360px;height:195px;border:0px solid #000;text-align:left; overflow-y: scroll;">
		</div>
		</code>
	</div>
	<input type="submit" name="back" value="Back">
	<br><br>';
}
//================================ SCHEDULE LIST ===========================================================================================
if ( $show == "schedule-list" ){
	exec('uci get wgetui.setting.schedule',$scheduleon);
	exec('grep "wgetui schedule-on" /etc/crontabs/root | awk \'{ print $2 }\'',$mulaijam);
	exec('grep "wgetui schedule-on" /etc/crontabs/root | awk \'{ print $1 }\'',$mulaimenit);
	exec('grep "wgetui schedule-off" /etc/crontabs/root | awk \'{ print $2 }\'',$stopjam);
	exec('grep "wgetui schedule-off" /etc/crontabs/root | awk \'{ print $1 }\'',$stopmenit);
	echo '<font color="blue"><b>Schedule download is '.$scheduleon[0].'<br>';
	echo '<div align="left">
	Start: '.$mulaijam[0].':'.$mulaimenit[0].'<br>
	Stop: '.$stopjam[0].':'.$stopmenit[0].'</b></font>
	</div>
	<br>Link List:<br>
	<code>
<textarea name="download-link" readonly rows="10" cols="78" style="font-family: Arial;font-size: 9pt;">
';
echo file_get_contents( "/www/pulpstone/wget/schedule-link.txt" );
echo '</textarea><br></code>
	<input type="submit" name="delete-schedule" value="Delete Schedule Download" >
	<input type="submit" name="back" value="Back"><br><br>';
}
//=============================== CURRENT DOWNLOAD LINKS AND QUEUE LINKS =================================================================
if ( $show == "link-list" ){
	echo '
	<div style="height:230px; overflow-y: scroll;">
		<b>Current Download Links:</b><br>
		<div class="ConnectionBox">
			<code>
				<div style="font-size: 11px;  word-wrap: break-word; white-space: pre-wrap; word-break: break-all; max-width:360px;height:195px;height:100%;border:0px solid #000;text-align:left;">'
.file_get_contents( "/www/pulpstone/wget/wget-download-link.txt" ).'
				</div>
			</code>
		</div>';
		echo '
		</textarea>
		</code>';
	if (file_exists('/www/pulpstone/wget/queue.txt')){
		echo '
		<b>Queue Links:</b><br>
		<div class="ConnectionBox">
			<code>
				<div style="font-size: 11px;  word-wrap: break-word; white-space: pre-wrap; word-break: break-all; max-width:360px;height:100%;border:0px solid #000;text-align:left;">'
.file_get_contents( "/www/pulpstone/wget/queue.txt" ).'
				</div>
			</code>
		</div>';
		echo '
		</textarea>
		</code>';
	}
	echo '
	</div>';
	if (file_exists('/www/pulpstone/wget/queue.txt')){
		echo '<input type="submit" name="delete-queue" value="Delete Queue Links">';
	}
	echo '
	<input type="submit" name="back" value="Back">
	<br>';
	echo $delete.'<br>';
}
// ==================================== END HERE ==========================================================================================
echo $warning[0];
// If wgetui is not running, show:
if (empty($wgetuistatus[0])){
	echo '<input type="submit" name="continue-wget" value="Continue"> ';
}
// If wgetui is not running and it's on the homepage, show:
if (empty($wgetuistatus[0]) && $show=="home"){
	echo '<input type="submit" name="wget-send" value="Start"> ';
}
// If wgetui is running and it's on the homepage, show:
if (!empty($wgetuistatus[0]) && $show=="home"){
	echo '<input type="submit" name="add-queue" value="Add to queue"> ';
}
// If wgetui is running, show:
if (!empty($wgetuistatus[0])){
	echo '<input type="submit" name="stop-wget" value="Stop"> ';
	echo '<input type="submit" name="link-list" value="Link List"> ';	
}
echo'<input type="submit" name="wget-log" value="Log"> ';
// If schedule download is set, show:
if ( $schedulestatus[0] == "on" ){
echo '<input type="submit" name="schedule-list" value="Schedule List">';
}
echo '
</form>
'.$schedule.'
</div>';

include 'footer.php';
?>
