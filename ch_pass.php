<?php
include 'header.php';
ceklogin();
css();
exec('grep user /root/passwd |awk -F" " \'{print $2}\'',$user);
exec('grep passwd /root/passwd |awk -F" " \'{print $2}\'',$pass);
if ($_GET['login']) {
 
     if ($_POST['username'] == $user[0]
         && $_POST['password'] == $pass[0]) {
         $new=$_POST['newpassword'];
         exec('echo "user root" > /root/passwd');
         exec('echo "passwd "'.$new.' >> /root/passwd');
         echo 'Root Password Change Success...';
         $_SESSION['loggedin'] = 0;
 
     } else echo " Invalid Username or Password";
 
}
 
echo '
<b>Change Password</b>
<br><br>
<form action="?login=1" method="post">
<input type="text" autofocus name="username" placeholder="Username" size="30" />
</br>
</br>
<input type="password" name="password" placeholder="Old Password" size="30" />
</br>
</br>
<input type="password" name="newpassword" placeholder="New Password" size="30" />
</br>
</br>
<input type="submit" value="Submit" /></br>

</form>';

include 'footer.php';
?>