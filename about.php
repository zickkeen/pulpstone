<?php
include 'header.php';
ceklogin();
css();
echo '
<b>System</b><br><br>';

exec('profile hw-info',$out);
$arrlength=count($out);
for($x=0;$x<$arrlength;$x++)
  {
  echo $out[$x]. "<br>";
  }

echo '
Repacked by Pulpstone OpenWrt 2016';

include 'footer.php';
?>
