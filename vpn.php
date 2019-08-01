<?php
include 'header.php';
ceklogin();
$show="home";
if($_POST['delete']){
    $file_config=$_POST['config'];
    exec('rm -f /root/crt/'.$file_config);
    header( "refresh:0;url=vpn.php" );
    exit;
}

if($_POST['apply']){
    exec('grep use_config /root/config |awk -F"\'" \'{print $2}\'',$y);
    $use_config=$_POST['use_config'];
    $file_config=$_POST['config'];
    if ($use_config=='yes'){
    /*if (strpos($file_config,'.ovpn') == false) {
        header( "refresh:3;url=vpn.php" );
        css();
        echo "<font color=red><b>Apply failed, choose only *.ovpn</b></font>";
        exit();
    }*/
        exec('vpn use-config yes',$var);
        exec('vpn file-config '.$file_config,$var);
        $show="home";
        $status="<br><b><font color='green'>OpenVPN Set: ".$_POST['config']."</font></b>";
    }
    else{
       exec('vpn use-config no',$var);
       exec('vpn file-config '.$file_config,$var);
        if($y[0]=='yes'){
            header( "refresh:0;url=vpn.php" );
            exit;
        }
        else{
            $server=$_POST['server'];
            $user=$_POST['user']; 
            $pass=$_POST['pass'];
            exec('vpn server '.$server,$var3);
            exec('vpn user-pass '.$user.' '.$pass,$var3);   
            $show="home";
            $status="<br><b><font color='green'>OpenVPN Set: ".$_POST['config']."</font></b>";        }
    }
}

css();
if($_POST['back']){
    $show="home";
}
if($_POST['edit']){
    $show="edit";
}
if($_POST["update"]){
    if(!empty($_POST['akunvpn']) && !empty($_POST['rename']) && strpos($_POST['rename'],'.ovpn') !== false || strpos($_POST['rename'],'.txt') !== false){
        file_put_contents($_POST['filename'], $_POST['akunvpn']);
        $rename="/root/crt/".$_POST['rename'];
        rename($_POST['filename'], $rename);
        $nameonly = str_replace('/root/crt/', '', $_POST['filename']);
        $show="home";
        $status="<br><b><font color=\"green\">".$_POST['rename']." has been updated</font></b>";
    }
    else{
        $show="home";
       
    }
}

if ($show == "home"){
    echo " <font face='tahoma'><br>
    <form action=\"upload_crt.php\" method=\"post\"
    enctype=\"multipart/form-data\">
    <input type=\"file\" name=\"file\" id=\"file\" size=\"10\">
    <input type=\"submit\" name=\"upload\" value=\"Upload\">
    </form>";

    exec('cat /root/user.txt',$out);
    exec('vpn server show',$var1);
    exec('vpn config-show',$h);
    echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
    echo "</br>";
    exec('grep use_config /root/config |awk -F"\'" \'{print $2}\'',$y);
    if ($y[0]=='yes'){
        echo '<input type="checkbox" name="use_config" value="yes" checked>Gunakan file Config<br><br>';
        exec('ls /root/crt |grep -i -e .ovpn -e .txt',$list);
        echo 'Konfigurasi:<br><br> <select name="config">';
        $x=0;
        while($x<count($list)){
            if($list[$x]==$h[0]){
                echo "<option value=\"$list[$x]\" selected>$list[$x]</option>";
            }
            else{
                echo "<option value=\"$list[$x]\">$list[$x]</option>";
            }
            $x++;
        }
        echo "</select> ";
        echo '<input name="edit" type="submit" value="Edit"> ';
        echo "<input name=\"delete\" type=\"submit\" value=\"Delete\"/>  ";
    }
    else{
        echo '<input type="checkbox" name="use_config" value="yes" >Use file Config';
        echo "<br><br>Server: <input type=\"text\" name=\"server\" value=\"$var1[0]\"/>";
        echo " <br><br>";
        echo "User--: <input type=\"text\" name=\"user\" value=\"$out[0]\"/>";
        echo "<br><br>";
        echo "Pass--: <input type=\"password\" name=\"pass\" value=\"$out[1]\"/>";
    }
    echo "<input name=\"apply\" type=\"submit\" value=\"Apply\"/>";
    echo '</form>';
    echo $status;
    echo '<br>';
}
if($show == "edit"){
    $dir = '/root/crt/';
    $oldfilename = $dir . $_POST["config"];
    $filecontent = file_get_contents($oldfilename);
    $nameonly = str_replace('/root/crt/', '', $oldfilename);
    //echo '<b><font color="green">Now editing: '.$nameonly.'</font></b>';
    echo '
    <form action="" method="post">
    File Name: <input type="text" name="rename" value="'.$nameonly.'">
    <br><br> ';
	echo '
    <textarea class="scroll" name="akunvpn" rows="12" cols="50" >';
    echo htmlentities($filecontent);
    echo '</textarea><br><br>
    <input type="hidden" name="filename" value="'.$oldfilename.'">
    <input name="update" type="submit" value="Update File">
    <input name="back" type="submit" value="Back"> <br><br>
    </form>
    ';
}


echo '
</div>';

include 'footer.php';

?>