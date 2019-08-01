<?php
include 'header.php';
ceklogin();
css();
echo '
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: "dial.log",
                success: function(result) {
                    $("#dial").html(result);
                }
            });
	$(document).ready(function() {
		$.ajaxSetup({ cache: false });
			});
		var textarea = document.getElementById("dial");
		textarea.scrollTop = textarea.scrollHeight;
        }, 1000);
    });
</script>';
if($_POST['start']){
    $pf=$_POST['profile'];
    exec('profile start '.$pf.' > /dev/null 2>&1 &');
}
if($_POST['wan']){
    exec('vpn wan > /dev/null 2>&1 &');
}
if($_POST['stop']){
    exec('profile stop');
}
if($_POST['restart']){
    exec('profile start > /dev/null 2>&1 &');
}
if($_POST['stop-openvpn']){
	exec ('killall openvpn');
}
if($_POST['restart-openvpn']){
	exec ('profile restart-openvpn &');
}
exec("echo \"($(grep 'profile ' /root/config |grep -v source |awk -F\"'\" '{print $2}'))\"",$profile);
exec("echo \"($(grep \"file_config \" /root/config |awk -F\"'\" '{print $2}'))\"",$vpn);
echo '
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: "vpn.log",
                success: function(result) {
                    $("#vpn").html(result);
                }
            });
		var textarea = document.getElementById("vpn");
		textarea.scrollTop = textarea.scrollHeight;
        }, 1000);
    });
</script>';
echo '
<div id="side-by-side">
    <div class="sub"><strong>Dial Up Log '.$profile[0].'</strong>
			<pre>
			<div id="dial" class="scroll"></div>
			</pre>
		<form action='.$_SERVER['PHP_SELF'].' method="post">
		<input type="submit" name="start" value="Start Internet">
		<input type="submit" name="stop" value="Stop Internet">
		</form>
    </div>
</div>
</div>
&nbsp;&nbsp;&nbsp;
<div class="main-box">
<div id="side-by-side">
    <div class="sub"><strong>OpenVPN Log '.$vpn[0].'</strong>
			<pre>
			<div id="vpn" class="scroll"></div>
			</pre>
		<form action='.$_SERVER['PHP_SELF'].' method="post">
		<input type="submit" name="restart-openvpn" value="Restart OpenVPN">
        <input type="submit" name="wan" value="OpenVPN">
		<input type="submit" name="stop-openvpn" value="Stop OpenVPN">
		</form>
    </div>
</div>';

include 'footer.php';
?>