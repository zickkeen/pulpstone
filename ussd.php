<?php
include 'header.php';
css();
if($_POST['cancel']){

     echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
     echo "<input type=\"text\" autofocus name=\"ussd\" size=\"35\" value=\"$ssid[0]\" placeholder=\"USSD\"/><br><br>";
     echo "<input name=\"send\" type=\"submit\" value=\"Kirim\" />
     <input name=\"cancel\" type=\"submit\" value=\"Batal\" />
     </form>";
     echo "<br>";
     echo '<small>
     <div class="ussd-inner scroll">';
     exec('gsm ussd -e',$out);  
     $notend=0;
     $arrlength=count($out);
     for($x=0;$x<$arrlength;$x++)
     {
        if ($out[$x] == "NOTEND"){
        $notend=1;
        }else{
        echo $out[$x]. "<br>";}
     }
     echo '
    </div></small>';
    if ($notend == 1){
     echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
     echo "<input name=\"cancel\" type=\"submit\" value=\"Batalkan sesi\" />
     </form>";}
    echo '
    </div>';
	include 'footer.php';
    exit;

}
if($_POST['send']){
    $ussd=$_POST['ussd'];

     echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
     echo "<input type=\"text\" autofocus name=\"ussd\" size=\"35\" value=\"$ssid[0]\" placeholder=\"USSD\"/><br><br>";
     echo "<input name=\"send\" type=\"submit\" value=\"Kirim\" />
     <input name=\"cancel\" type=\"submit\" value=\"Batal\" />
     </form>";
     echo "<br>";
     echo '
     <small><div class="ussd-inner scroll">';
     exec('gsm ussd '.$ussd,$out);  
     $notend=0;
     $arrlength=count($out);
     for($x=0;$x<$arrlength;$x++)
     {
        if ($out[$x] == "NOTEND"){
        $notend=1;
        }else{
        echo $out[$x]. "<br>";}
     }  
     echo '
    </div></small>';
    if ($notend == 1){
     echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
     echo "<input name=\"cancel\" type=\"submit\" value=\"Batalkan sesi\" />
     </form>";}
    echo '
    </div>';
	include 'footer.php';
    exit;

    }

echo "<form action=\"".$PHP_SELF."\" method=\"post\">";
echo "<input type=\"text\" autofocus name=\"ussd\" size=\"35\" value=\"$ssid[0]\" placeholder=\"USSD\"/><br><br>";
echo "<input name=\"send\" type=\"submit\" value=\"Kirim\" />
     <input name=\"cancel\" type=\"submit\" value=\"Batal\" />
</form>";
echo "<br>";
echo '
</div>
</div>';
include 'footer.php';
?>
