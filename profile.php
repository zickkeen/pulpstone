<?php
include 'header.php';
ceklogin();
if($_POST['edit']){
     $pf=$_POST['profile'];
     exec('echo '.$pf.'> /tmp/pf');
header( "refresh:0;url=edit_profile.php" );
}
css();
if($_POST['update']){
    echo '
    <br><br><br>';
    $pf=$_POST['profile'];
    exec('profile update list');
    exec('cat /tmp/update',$list);
    if ($list[0] != 'gagal'){       
     echo "daftar profile yang ada di server";
     echo "<br>";
     echo "<br>";
     echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
     echo '
     Pilih Profil:
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
     <input type="submit" name="install" value="Download" /><br><br>';
     echo "Masukan nama Profil :<input name=\"mprofile\" size=\"8\" value=\"$key[0]\"/>";
     echo '<input type="submit" name="install" value="Download" />';
     echo "</form>";
	 
include 'footer.php';

     exit;
 
    } else echo "update gagal";
  }

if($_POST['install-scrypt']){
    $pf=$_POST['scrypt'];
    exec('profile scrypt-list');
    exec('cat /tmp/update',$list);
    if ($list[0] != 'gagal'){     
     echo '<br><br><br>';  
     echo "daftar scrypt yang ada di server";
     echo "<br>";
     echo "<br>";
     echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
     echo '
     nama scrypt:
     <select name="scrypt">';
     $x=0;
     while($x<count($list))
    {
     echo "
     <option value=\"$list[$x]\">$list[$x]</option>";
      $x++;
     }
     echo '
     </select>
     <input type="submit" name="installsc" value="Install" />';
     echo "</form>";

	 include 'footer.php';

     exit;
 
    } else echo "<font face='tahoma'>update gagal";
  }
if($_POST['update-web']){
exec('profile update-web',$list);
$arrlength=count($list);
for($x=0;$x<$arrlength;$x++)
  {
  echo $list[$x]. "<br>";
  }
  
include 'footer.php';
exit;

}
if($_POST['installsc']){
    $pf=$_POST['scrypt'];
    echo '<br><br><br><br>';
    exec('profile scrypt-install '.$pf,$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }

include 'footer.php';
exit;

  }

if($_POST['install']){
    echo '<br><br><br>';
    $pf=$_POST['mprofile'];
    if ( "x".$pf == "x"){
    $pf=$_POST['profile'];
    $pf=explode(" ",$pf);
    $prof=$pf[0];
}else{
    $prof=$pf;}
    exec('profile update '.$prof,$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
include 'footer.php';
exit;

  }

if($_POST['update-scrypt']){
    $pf=$_POST['profile'];
    echo '<br><br><br>';
    exec('profile update',$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
include 'footer.php';
exit;

  }
if($_POST['set-modem']){
    $pf=$_POST['port-modem'];
    echo '<br><br><br>';
    exec('gsm set port '.$pf,$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
include 'footer.php';
exit;

  }

if($_POST['set-iface']){
    $pf=$_POST['iface-gsm'];
    echo '<br><br><br>';
    exec('gsm set interface '.$pf,$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
include 'footer.php';
exit;

  }

if($_POST['set-ipad']){
    $pf=$_POST['ip-source'];
    echo '<br><br><br>';
    exec('gsm set ipsource '.$pf,$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }
include 'footer.php';
exit;

  }


if($_POST['hapus']){
    $pf=$_POST['profile'];
    exec('rm -f /root/profiles/'.$pf,$out);
  }

exec('ls /root/profiles',$list);
exec('ls /root/ipsource',$list1);
echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
echo '<br><br>';
exec('cat /root/config |grep -w \'profile \' |awk -F"\'" \'{print $2}\'',$o);
exec('cat /etc/config/gsm |grep -w \'IPAD \' |awk -F"\'" \'{print $2}\'',$o);
$profile=$o[0];
$ipsource=$o[1];
echo '
Profile :
<select name="profile">';
$x=0;
while($x<count($list))
  {
if ( $profile == $list[$x] ){

   echo "
   <option value=\"$list[$x]\" selected>$list[$x]</option>";}
else {

   echo "
   <option value=\"$list[$x]\">$list[$x]</option>";}
   $x++;
}
echo '
</select>
<input type="submit" name="update" value="Update list" />
<input type="submit" name="edit" value="Edit" />
<input type="submit" name="hapus" value="Hapus" />';
echo "</form>";

echo "<br>";
//echo "<br>";

    echo "
         <form action=\"upload_profile.php\" method=\"post\"
         enctype=\"multipart/form-data\">
        <label for=\"file\">Upload:</label>
        <input type=\"file\" name=\"file\" id=\"file\">
        <input type=\"submit\" name=\"upload\" value=\"Upload\">
        </form>";

echo "<br>";
//echo "<br>";
echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
echo '<input type="submit" name="update-web" value="Update Web" />';
echo "</form>";

echo ' <br>';
echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
exec('cat /etc/config/gsm |grep ttyUSB |awk -F"\'" \'{print $2}\'',$def);
$cur=$def[0];
exec('ls /dev |grep ttyUSB',$out);
echo '
Port Modem :
<select name="port-modem">';
$x=0;
while($x<count($out)){
if ( $cur == $out[$x] ){

   echo "
   <option value=\"$out[$x]\" selected>$out[$x]</option>";}
else {

   echo "
   <option value=\"$out[$x]\">$out[$x]</option>";}
   $x++;
}
echo '
</select>
<input type="submit" name="set-modem" value="Set Port Modem" /><br><br>


Interface :
<select name="iface-gsm">';
exec('cat /etc/config/gsm |grep IFACE |awk -F"\'" \'{print $2}\'',$def2);
$cur2=$def2[0];
exec('uci show network |grep 3g.proto|awk -F"." \'{print $2}\'',$out2);
exec('uci show network |grep 4g.proto|awk -F"." \'{print $2}\'',$out2);
exec('uci show network |grep tethering.proto|awk -F"." \'{print $2}\'',$out2);
exec('uci show network |grep hilink.proto|awk -F"." \'{print $2}\'',$out2);
exec('uci show network |grep wan.proto|awk -F"." \'{print $2}\'',$out2);
exec('uci show network |grep evdo.proto|awk -F"." \'{print $2}\'',$out2);
$x=0;
while($x<count($out2)){
if ( $cur2 == $out2[$x] ){

   echo "
   <option value=\"$out2[$x]\" selected>$out2[$x]</option>";}
else {

   echo "
   <option value=\"$out2[$x]\">$out2[$x]</option>";}
   $x++;
}

echo '
</select>
<input type="submit" name="set-iface" value="Set Interface" /><br><br>


IP Source :
<select name="ip-source">';
$x=0;
while($x<count($list1))
  {
if ( $ipsource == $list1[$x] ){

   echo "
   <option value=\"$list1[$x]\" selected>$list1[$x]</option>";}
else {

   echo "
   <option value=\"$list1[$x]\">$list1[$x]</option>";}
   $x++;
}

echo '
</select>
<input type="submit" name="set-ipad" value="Set IP Source"/>';
echo '<br><br>';


echo "</form>";
include 'footer.php';
?>
