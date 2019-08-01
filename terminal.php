<?php
include 'header.php';
ceklogin();
css();
echo '
<script type="text/javascript">
    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: "result.txt",
                success: function(result) {
                    $("#show").html(result);
                }
            });
    $(document).ready(function() {
        $.ajaxSetup({ cache: false });
            });
        var textarea = document.getElementById("show");
        textarea.scrollTop = textarea.scrollHeight;
        }, 1000);
    });
</script>';
exec('echo "" > /www/pulpstone/result.txt');
if($_POST['command'])
{
$kill = file_get_contents("/www/pulpstone/command.txt");
exec("killall $kill > /www/pulpstone/result.txt");
$command = $_POST['command'];
exec("$command > /www/pulpstone/result.txt &");
exec("echo $command > /www/pulpstone/command.txt");  
}
?>
<form action="" method="post">
Terminal Command:<br><input type="text" autofocus name="command" size="40" value="" placeholder="For multiple commands, use: && For instace: cd /mnt && pwd"><br>
<pre>
<div id="show" style="font-size: 11px;  word-wrap: break-word; max-width:360px;height:195px;border:0px solid #000;text-align:center;overflow-y: scroll;"></div>
</pre>
</form>
</div>
<?php
include 'footer.php';

echo '
</body>
</html>';
?>
