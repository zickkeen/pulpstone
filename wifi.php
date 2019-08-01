<?php
include 'header.php';
ceklogin();
css();
echo '<brr>';
if($_POST['Submit']){
    $ssid=$_POST['ssid'];
    $key=$_POST['key'];
	exec('uci set wireless.@wifi-iface[0].ssid="'.$ssid.'"');
    exec('uci set wireless.@wifi-iface[0].key="'.$key.'"');
    exec('uci set wireless.@wifi-iface[0].encryption=psk');
    exec('uci commit wireless');
    exec('wifi');
    }

exec('uci show wireless.@wifi-iface[0].ssid |awk -F"\'" \'{print $2}\'',$ssid);
exec('uci show wireless.@wifi-iface[0].key |awk -F"\'" \'{print $2}\'',$key);
echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
echo "SSID <input type='text' name='ssid' value=\"$ssid[0]\" size='10'/>    ";
echo "KEY <input type='Password' name='key' value=\"$key[0]\"size='10'/>";
echo '<br><br>';
echo '<input class="btn-wide" name="Submit" type="submit" value="Submit" />
</form>';
echo '<br>
<small>
<div class="main-inner" >';
exec('profile wifi-clients',$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
 echo '</div>';
include 'footer.php';
?>
