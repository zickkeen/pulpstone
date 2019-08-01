<?php
include 'header.php';
ceklogin();
css();
$show="home";
echo '
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: "outbox.log",
                success: function(result) {
                    $("#outbox").html(result);
                }
            });
	$(document).ready(function() {
		$.ajaxSetup({ cache: false });
			});
		var textarea = document.getElementById("outbox");
		textarea.scrollTop = textarea.scrollHeight;
        }, 1000);
    });
</script>';
if(isset($_POST['sms-read-all'])){
	{
	exec('gsm sms read all',$out);
	$show="inbox";
	}
	}
if(isset($_POST['sms-send'])){
    {
	$show="send sms";
    }
	}
if(isset($_POST['send-now'])){
	{
	$number=$_POST['number'];
	$pesan=$_POST['pesan'];
	exec('gsm sms send '.$number.' '.$pesan,$out);
	if (strpos($out[2],'sms sukses terkirim ke') !== false) {
		exec('echo "<b>DATE:</b>$(date +%d-%m-%Y_%H:%M:%S)<br><b>NOMER:</b>'.$number.'<br><b><b>ISI PESAN:</b></b><br>'.$pesan.'<br>===============<br>" >> /www/outbox.log');
	}
	echo "<font color=blue><b>".$out[2]."</b></font>";
	$show="outbox";
	}
	}
if(isset($_POST['inbox'])){
    {
	exec('gsm sms read unread',$out);
	$show="inbox";
    }
	}
if(isset($_POST['delete-inbox'])){
	{
	exec('gsm sms del all',$out);
	$show="inbox";
	}
	}
if(isset($_POST['reset-modem'])){
	{
	exec('gsm at ATZ0');
	exec('gsm status');
	$show="log";
	echo '<b><font color="blue">The modem has been reset...</font></b>';
	}
	}
echo '
<form action='.$_SERVER['PHP_SELF'].' method="post">
<input type="submit" name="sms-send" value="Send SMS">
<input type="submit" name="inbox" value="Inbox SMS">
</form>';
if ( $show == "home" )
	{
	echo '
	<pre>
	<div class="msg-show scroll">';
	$arrlength=count($out);
	for($x=0;$x<$arrlength;$x++)
		{
		echo $out[$x]."\n";
		}
	echo '</pre>
	<form action='.$_SERVER['PHP_SELF'].' method="post">
	<input type="submit" name="sms-read-all" value="Read All Inbox">
	<input type="submit" name="delete-inbox" value="Delete Inbox">
	</form>
	</div>';
	}
if ( $show == "inbox" )
	{
	echo '
	<pre>
	<div class="msg-show scroll">';
	$arrlength=count($out);
	for($x=0;$x<$arrlength;$x++)
		{
		echo $out[$x]."\n";
		}
	echo '</pre>
	<form action='.$_SERVER['PHP_SELF'].' method="post">
	<input type="submit" name="sms-read-all" value="Read All Inbox">
	<input type="submit" name="delete-inbox" value="Delete Inbox">
	</form>
	</div>';
	}
if ( $show == "send sms" )
	{
	echo '
	<form action='.$_SERVER['PHP_SELF'].' method="post">
	<br>
	<input type="text" autofocus name="number" size="15" placeholder="Destination Number"><br>
	<br>
	<textarea class="msg-show scroll" name="pesan" rows="8" cols="30" placeholder="Message"></textarea><br><br>
	<input type="submit" name="send-now" value="Send Now">
	</form>
	</div>';
	}
echo '
<br>
</div>';

include 'footer.php';
?>