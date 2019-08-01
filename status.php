<?php
include 'header.php';
ceklogin();
    $event=0;
    $st=0;
if($_POST['start']){
    $pf=$_POST['profile'];
    exec('profile start '.$pf.' > /dev/null 2>&1 &');
   $list="Profile Started .. OK";
    $event=1;    
    $st=2;
    header( "refresh:1;url=log.php" );
}
if($_POST['stop']){
    exec('profile stop');
    $list="Profile Stoped .. OK";

    $event=1;
    $st=2;    
    header( "refresh:1;url=status.php" );
}
if($_POST['restart']){
    exec('profile start > /dev/null 2>&1 &');
    $event=1;    
    $st=2;
    $list="Profile restarted .. OK";
    header( "refresh:1;url=log.php" );
}

if($_POST['info']){
    $pf=$_POST['profile'];
    $st=3;
}

if($_POST['reg']){
    $param=$_POST['network'];
    exec('gsm set network '.$param);
    sleep(3);
    $st=1;
}



css();
if ( $event == 1 )
{
    echo '<br>';
echo $list;
echo '
    <br>
    Harap Menunggu.... 
</div>';

include 'footer.php';
exit;
}
if ( $event == 0 )
{
exec('cat /tmp/prf',$o);
if ( $o[0] == 'started' ) {
echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
echo '
<input type="submit" name="refresh" value="Refresh Status"/>
<input type="submit" name="restart" value="Restart Profile"/>
<input type="submit" name="stop" value="Stop Profile"/>
</form>';
}
else
{
exec('ls /root/profiles',$list);
echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
echo '
profile :
<select name="profile">';
$x=0;
while($x<count($list))
  {
   echo "
   <option value=\"$list[$x]\">$list[$x]</option>";
   $x++;
  }
echo '
</select>
<input type="submit" name="start" value="Start Profile"/>
<input type="submit" name="info" value="Info Profile"/>
</form>';
}

echo "<br>";
if($_POST['all-status']){
$st=1;
}

if($_POST['edge-only']){
$st=1;
exec('gsm jump edge',$ot);
exec('uci set network.3g.service=gprs_only',$ot);
sleep(10);
}

if($_POST['tigag-only']){
$st=1;
exec('gsm jump 3g',$ot);
exec('uci set network.3g.service=umts_only',$ot);
sleep(10);
}


if ( $st == 3 ){
   exec('profile info '.$pf,$out);
   $st=0;
}else{
if ( $st == 1){ 
exec('gsm status',$out);
}else{
$st=0;
exec('profile status-koneksi',$out);
}}
if ($st <> 2){
echo '
<div class="main-inner">';
}

$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
}
if ($st <> 2){
echo '</div>';
}
if ($st == 0){
echo "<br><form action=\"".$PHP_SELF."\" method=\"post\">";
echo '
<input class="btn-wide" type="submit" name="all-status" value="Status Modem"/>
</form>';
}else{
if ($st == 1){
echo "<br><form action=\"".$PHP_SELF."\" method=\"post\">";
echo '
<input class="btn" type="submit" name="edge-only" value="Edge Only"/>
<input class="btn" type="submit" name="tigag-only" value="3G Only"/>
<select name="network" style=""/>
<option value="">Select Network</option>\"
<option value=\"auto\">Auto</option>\"
<option value=\"51001\">Indosat</option>\"
<option value=\"51011\">XL</option>\"
<option value=\"51089\">3</option>\"
<option value=\"51010\">Telkomsel</option>\"
</select>
<br>
<br>
<input class="btn-wide" type="submit" name="reg" value="Register"/>
</form>';
}}
echo '
</div>';

include 'footer.php';

?>
