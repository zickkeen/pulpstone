<?php
include 'header.php';
ceklogin();
css();

$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['file']['name']);


echo "<p>";

if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
   echo "Upload file profile sukses.\n";
   exec('cp -f '.$uploadfile.' /root/profiles');
} else {
   echo "Upload gagal";
}
echo '
</div>';
include 'footer.php';
?> 